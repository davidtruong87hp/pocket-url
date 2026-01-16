<?php

namespace App\Providers;

use App\Services\MessageQueue\ConsumerManager;
use Illuminate\Support\ServiceProvider;

class SwooleRabbitMQServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ConsumerManager::class, function () {
            return new ConsumerManager(
                consumerConfigs: config('rabbitmq.consumers'),
                maxWorkers: config('rabbitmq.max_workers', 10),
                connectionPoolSize: config('rabbitmq.connection_pool_size', 3)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
