<?php

namespace App\Services\MessageQueue;

use Illuminate\Support\Facades\Log;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use Throwable;

/**
 * Worker coroutine
 * Processes jobs from the job channel
 */
class Worker
{
    private bool $running = true;

    public function __construct(
        private int $id,
        private ?Channel $jobChannel,
        private Statistics $stats
    ) {}

    public function start(): void
    {
        Log::info("Starting worker {$this->id}");

        while ($this->running) {
            $job = $this->jobChannel->pop(1.0);

            if ($job === false) {
                Coroutine::sleep(0.01);

                continue;
            }

            $this->processJob($job);
        }
    }

    public function stop(): void
    {
        $this->running = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function processJob(array $job): void
    {
        $startTime = microtime(true);

        try {
            /** @var MessageHandlerInterface */
            $handler = $job['handler'];
            $message = $job['message'];

            $handler->handle($message);

            $processingTime = (microtime(true) - $startTime) * 1000;
            $this->stats->recordSuccess($processingTime);

            Log::debug("Worker {$this->id} completed job", [
                'consumer' => $job['consumer_name'],
                'processing_time_ms' => round($processingTime, 2),
            ]);
        } catch (Throwable $e) {
            $this->handleFailure($e, $job);
        }
    }

    public function handleFailure(Throwable $e, array $job): void
    {
        $this->stats->recordFailure();

        Log::error("Worker {$this->id} failed to process job", [
            'error' => $e->getMessage(),
        ]);

        if (isset($job['handler'], $job['message'])) {
            $job['handler']->handleFailure($job['message'], $e);
        }
    }
}
