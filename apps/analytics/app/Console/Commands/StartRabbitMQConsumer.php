<?php

namespace App\Console\Commands;

use App\Services\MessageQueue\ConsumerManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Swoole\Coroutine;
use Swoole\Runtime;
use Throwable;

class StartRabbitMQConsumer extends Command
{
    protected $signature = 'rabbitmq:consume';

    protected $description = 'Start the RabbitMQ consumer';

    public function handle()
    {
        $this->info('Starting RabbitMQ consumer...');

        Runtime::enableCoroutine(
            SWOOLE_HOOK_ALL && ~SWOOLE_HOOK_SOCKETS && ~SWOOLE_HOOK_STREAM_FUNCTION
        );

        Coroutine\run(function () {
            try {
                $consumer = app(ConsumerManager::class);
                $consumer->start();

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
