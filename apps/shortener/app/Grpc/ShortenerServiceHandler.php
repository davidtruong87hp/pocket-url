<?php

namespace App\Grpc;

use Shortener\ResolveShortcodeRequest;
use Shortener\ResolveShortcodeResponse;
use Shortener\ShortenerServiceInterface;
use Spiral\RoadRunner\GRPC;

class ShortenerServiceHandler implements ShortenerServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function ResolveShortcode(
        GRPC\ContextInterface $ctx,
        ResolveShortcodeRequest $in
    ): ResolveShortcodeResponse {
        $response = new ResolveShortcodeResponse;

        $response->setSuccess(true);
        $response->setDestinationUrl('https://google.com');
        $response->setError('');
        $response->setMetadata(new \Shortener\ShortcodeMetadata([
            'short_code' => 'abcDEF',
            'short_url' => 'https://google.com',
            'title' => 'Google',
            'created_at' => date('Y-m-d H:i:s'),
        ]));

        return $response;
    }
}
