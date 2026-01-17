<?php

namespace App\Console\Commands;

use App\Services\MessageQueue\ConsumerManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Swoole\Coroutine;
use Swoole\Runtime;
use Throwable;

class StartTestRabbitMQConsumer extends Command
{
    protected $signature = 'rabbitmq:consume:test';

    protected $description = 'Start RabbitMQ consumer for testing';

    private const TEST_EXCHANGE = 'test.analytics.exchange';

    private const TEST_QUEUE = 'test.analytics_clicks';

    public function handle()
    {
        Runtime::enableCoroutine(
            SWOOLE_HOOK_ALL && ~SWOOLE_HOOK_SOCKETS && ~SWOOLE_HOOK_STREAM_FUNCTION
        );

        Coroutine\run(function () {
            try {
                $consumersConfig = [
                    [
                        'name' => 'test_click_tracker',
                        'exchange' => self::TEST_EXCHANGE,
                        'exchange_type' => 'topic',
                        'queue' => self::TEST_QUEUE,
                        'routing_key' => 'click.tracked',
                        'handler' => \App\Jobs\ProcessClickEvent::class,
                        'instances' => 1,
                        'durable' => true,
                        'prefetch_count' => 10,
                        'use_dlq' => true,
                    ],
                ];

                $consumer = new ConsumerManager(
                    consumerConfigs: $consumersConfig,
                    maxWorkers: 5,
                    connectionPoolSize: 2
                );

                $consumer->start();

                $this->info('✅ Test consumer started', [
                    'queue' => self::TEST_QUEUE,
                    'exchange' => self::TEST_EXCHANGE,
                ]);

                // Keep the coroutine alive
                while (true) {
                    Coroutine::sleep(60);
                }
            } catch (Throwable $e) {
                $this->error('Error: '.$e->getMessage());

                Log::error('Consumer error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });

        return Command::SUCCESS;
    }
}
