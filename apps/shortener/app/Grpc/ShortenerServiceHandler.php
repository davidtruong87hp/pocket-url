<?php

namespace App\Grpc;

use App\Repositories\ShortenedUrlRepository;
use Shortener\ResolveShortcodeRequest;
use Shortener\ResolveShortcodeResponse;
use Shortener\ShortenerServiceInterface;
use Spiral\RoadRunner\GRPC;

class ShortenerServiceHandler implements ShortenerServiceInterface
{
    public function __construct(private ShortenedUrlRepository $shortenedUrlRepository) {}

    public function ResolveShortcode(
        GRPC\ContextInterface $ctx,
        ResolveShortcodeRequest $in
    ): ResolveShortcodeResponse {
        $shortenedUrl = $this->shortenedUrlRepository->getByShortCode($in->getShortcode());
        $response = new ResolveShortcodeResponse;

        if (! $shortenedUrl) {
            $response->setSuccess(false);
            $response->setError('Not found');
            $response->setDestinationUrl('');

            return $response;
        }

        $response->setSuccess(true);
        $response->setDestinationUrl($shortenedUrl->url);
        $response->setError('');

        $shortDomain = ! empty($in->getDomain()) ? $in->getDomain() : config('shortener.short_domain');

        $response->setMetadata(new \Shortener\ShortcodeMetadata([
            'short_code' => $shortenedUrl->shortcode,
            'short_url' => "http://{$shortDomain}/{$shortenedUrl->shortcode}",
            'title' => $shortenedUrl->title ?? '',
            'created_at' => $shortenedUrl->created_at->toAtomString(),
            'owner_id' => (string) $shortenedUrl->user_id,
            'short_domain' => $shortDomain,
        ]));

        return $response;
    }
}
