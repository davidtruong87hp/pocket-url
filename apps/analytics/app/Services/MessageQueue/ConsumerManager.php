<?php

namespace App\Services\MessageQueue;

use App\Services\MessageQueue\RabbitMQ\RabbitMQConnectionPool;
use App\Services\MessageQueue\RabbitMQ\RabbitMQConsumer;
use App\Services\MessageQueue\RabbitMQ\RabbitMQMessageHandler;
use Illuminate\Support\Facades\Log;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

/**
 * Orchestrates consumers and workers
 * Main entry point for the RabbitMQ consumer system
 */
class ConsumerManager
{
    private Channel $jobChannel;

    private RabbitMQConnectionPool $connectionPool;

    private Statistics $stats;

    private array $consumers = [];

    private array $workers = [];

    private bool $running = true;

    public function __construct(
        private array $consumerConfigs,
        private int $maxWorkers = 10,
        private int $connectionPoolSize = 5
    ) {
        $this->stats = new Statistics;
        $this->jobChannel = new Channel($maxWorkers * 2);
        $this->connectionPool = new RabbitMQConnectionPool($connectionPoolSize);
    }

    /**
     * Start all consumers and workers
     */
    public function start(): void
    {
        $totalConsumers = array_sum(array_column($this->consumerConfigs, 'instances'));

        Log::info('🚀 Starting RabbitMQ Consumer Manager', [
            'consumer_types' => count($this->consumerConfigs),
            'total_instances' => $totalConsumers,
            'workers' => $this->maxWorkers,
            'connection_pool_size' => $this->connectionPoolSize,
        ]);

        $this->startConsumers();
        $this->startWorkers();
        $this->startMonitor();

        Log::info('✅ RabbitMQ Consumer Manager started');
    }

    /**
     * Stop all consumers and workers gracefully
     */
    public function stop(): void
    {
        Log::info('🛑 Stopping RabbitMQ Consumer Manager...');
        $this->running = false;

        // Stop consumers
        foreach ($this->consumers as $consumer) {
            $consumer->stop();
        }

        // Wait for remaining jobs
        $this->waitForRemainingJobs();

        // Stop workers
        foreach ($this->workers as $worker) {
            $worker->stop();
        }

        // Cleanup
        $this->jobChannel->close();
        $this->connectionPool->close();

        Log::info('✅ RabbitMQ Consumer Manager stopped', [
            'total_processed' => $this->stats->getTotalProcessed(),
            'total_failed' => $this->stats->getTotalFailed(),
        ]);
    }

    /**
     * Get current statistics
     */
    public function getStats(): array
    {
        return [
            'running' => $this->running,
            'consumers' => [
                'total' => count($this->consumers),
                'running' => count(array_filter($this->consumers, fn ($c) => $c->isRunning())),
            ],
            'workers' => [
                'total' => count($this->workers),
                'running' => count(array_filter($this->workers, fn ($w) => $w->isRunning())),
            ],
            'queue' => [
                'length' => $this->jobChannel->length(),
                'capacity' => $this->jobChannel->capacity,
                'usage_pct' => round(($this->jobChannel->length() / $this->jobChannel->capacity) * 100, 2),
            ],
            'connection_pool' => $this->connectionPool->getStats(),
            'coroutines' => Coroutine::stats(),
            'performance' => $this->stats->toArray(),
        ];
    }

    private function startConsumers(): void
    {
        foreach ($this->consumerConfigs as $config) {
            $instances = $config['instances'] ?? 1;
            $handler = new RabbitMQMessageHandler($config['handler']);

            for ($i = 0; $i < $instances; $i++) {
                $name = "{$config['name']}:{$i}";

                $consumer = new RabbitMQConsumer(
                    name: $name,
                    config: $config,
                    connectionPool: $this->connectionPool,
                    jobChannel: $this->jobChannel,
                    messageHandler: $handler
                );

                $this->consumers[] = $consumer;

                Coroutine::create(fn () => $consumer->start());
            }
        }
    }

    private function startWorkers(): void
    {
        for ($i = 0; $i < $this->maxWorkers; $i++) {
            $worker = new Worker(
                id: $i,
                jobChannel: $this->jobChannel,
                stats: $this->stats
            );

            $this->workers[] = $worker;

            Coroutine::create(fn () => $worker->start());
        }
    }

    private function startMonitor(): void
    {
        Coroutine::create(function () {
            while ($this->running) {
                Coroutine::sleep(30);
                $this->logStats();
            }
        });
    }

    private function logStats(): void
    {
        Log::info('📊 RabbitMQ Consumer Stats', $this->getStats());
    }

    private function waitForRemainingJobs(): void
    {
        $timeout = 30;
        $start = time();

        while ($this->jobChannel->length() > 0 && (time() - $start) < $timeout) {
            Log::info('Waiting for remaining jobs...', [
                'remaining' => $this->jobChannel->length(),
            ]);
            Coroutine::sleep(1);
        }
    }
}
