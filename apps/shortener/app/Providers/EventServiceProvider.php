<?php

namespace App\Providers;

use App\Events\ShortenedUrlCreated;
use App\Events\ShortenedUrlDeleted;
use App\Events\ShortenedUrlUpdated;
use App\Listeners\InvalidateCacheOnShortenedUrlCreated;
use App\Listeners\InvalidateCacheOnShortenedUrlDeleted;
use App\Listeners\InvalidateCacheOnShortenedUrlUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ShortenedUrlCreated::class => [
            InvalidateCacheOnShortenedUrlCreated::class,
        ],
        ShortenedUrlUpdated::class => [
            InvalidateCacheOnShortenedUrlUpdated::class,
        ],
        ShortenedUrlDeleted::class => [
            InvalidateCacheOnShortenedUrlDeleted::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
