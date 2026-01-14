<?php

namespace App\Providers;

use App\Services\RabbitMQ\MessagePublisherInterface;
use App\Services\RabbitMQ\RabbitMQPublisher;
use Illuminate\Support\ServiceProvider;

class RabbitMQServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MessagePublisherInterface::class, function () {
            if (app()->environment('testing')) {
                return new class implements MessagePublisherInterface
                {
                    public function publish(string $pattern, array $data): void {}
                };
            }

            return new RabbitMQPublisher;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
