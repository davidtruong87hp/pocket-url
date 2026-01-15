<?php

namespace App\Listeners;

use App\Events\ShortenedUrlCreated;
use App\Jobs\PublishCacheInvalidationJob;

class InvalidateCacheOnShortenedUrlCreated
{
    /**
     * Handle the event.
     */
    public function handle(ShortenedUrlCreated $event): void
    {
        $shortenedUrl = $event->shortenedUrl;

        PublishCacheInvalidationJob::dispatch(
            'SHORTENED_URL_CREATED',
            $shortenedUrl->shortcode,
            $shortenedUrl->toArray()
        );
    }
}
