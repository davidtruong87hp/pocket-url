<?php

namespace Tests\Feature\Api\Grpc;

use App\Grpc\ShortenerServiceHandler;
use Database\Factories\ShortenedUrlFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Shortener\ResolveShortcodeRequest;
use Spiral\RoadRunner\GRPC;
use Tests\TestCase;

class ShortenerServiceHandlerTest extends TestCase
{
    use RefreshDatabase;

    private ShortenerServiceHandler $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ShortenerServiceHandler::class);
    }

    public function test_resolve_shortcode_success()
    {
        $shortenedUrl = ShortenedUrlFactory::new()->create();

        $request = new ResolveShortcodeRequest;
        $request->setShortcode($shortenedUrl->shortcode);

        /** @var GRPC\ContextInterface */
        $ctx = $this->mock(GRPC\ContextInterface::class);

        $response = $this->service->ResolveShortcode($ctx, $request);

        $this->assertTrue($response->getSuccess());
        $this->assertEquals($shortenedUrl->url, $response->getDestinationUrl());
        $this->assertEquals('', $response->getError());
    }

    public function test_resolve_shortcode_not_found()
    {
        $request = new ResolveShortcodeRequest;
        $request->setShortcode(Str::random(10));

        /** @var GRPC\ContextInterface */
        $ctx = $this->mock(GRPC\ContextInterface::class);

        $response = $this->service->ResolveShortcode($ctx, $request);

        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Not found', $response->getError());
        $this->assertEquals('', $response->getDestinationUrl());
    }

    public function test_resolve_multiple_shortcodes()
    {
        ShortenedUrlFactory::new(['shortcode' => 'abcDEF', 'url' => 'https://example.com/abcDEF'])->create();
        ShortenedUrlFactory::new(['shortcode' => 'ghiJKL', 'url' => 'https://example.com/ghiJKL'])->create();

        /** @var GRPC\ContextInterface */
        $ctx = $this->mock(GRPC\ContextInterface::class);

        // Test first shortcode
        $request = new ResolveShortcodeRequest;
        $request->setShortcode('abcDEF');

        $response = $this->service->ResolveShortcode($ctx, $request);

        $this->assertTrue($response->getSuccess());
        $this->assertEquals('https://example.com/abcDEF', $response->getDestinationUrl());
        $this->assertEquals('', $response->getError());

        // Test second shortcode
        $request = new ResolveShortcodeRequest;
        $request->setShortcode('ghiJKL');

        $response = $this->service->ResolveShortcode($ctx, $request);

        $this->assertTrue($response->getSuccess());
        $this->assertEquals('https://example.com/ghiJKL', $response->getDestinationUrl());
        $this->assertEquals('', $response->getError());
    }
}
