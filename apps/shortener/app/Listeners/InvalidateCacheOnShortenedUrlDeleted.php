<?php

namespace App\Listeners;

use App\Events\ShortenedUrlDeleted;
use App\Jobs\PublishCacheInvalidationJob;

class InvalidateCacheOnShortenedUrlDeleted
{
    /**
     * Handle the event.
     */
    public function handle(ShortenedUrlDeleted $event): void
    {
        PublishCacheInvalidationJob::dispatch('SHORTENED_URL_DELETED', $event->shortcode);
    }
}
