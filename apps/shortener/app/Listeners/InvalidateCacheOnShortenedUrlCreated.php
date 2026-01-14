<?php

namespace App\Listeners;

use App\Events\ShortenedUrlCreated;
use App\Http\Resources\Link\ShortenedUrlResource;
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
            (new ShortenedUrlResource($shortenedUrl))->toArray(request())
        );
    }
}
