<?php

namespace App\Listeners;

use App\Events\ShortenedUrlUpdated;
use App\Http\Resources\Link\ShortenedUrlResource;
use App\Jobs\PublishCacheInvalidationJob;

class InvalidateCacheOnShortenedUrlUpdated
{
    /**
     * Handle the event.
     */
    public function handle(ShortenedUrlUpdated $event): void
    {
        $shortenedUrl = $event->shortenedUrl;

        PublishCacheInvalidationJob::dispatch(
            'SHORTENED_URL_UPDATED',
            $shortenedUrl->shortcode,
            (new ShortenedUrlResource($shortenedUrl))->toArray(request())
        );
    }
}
