<?php

namespace Tests\Feature\Api\Link;

use Database\Factories\ShortenedUrlFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ViewShortenedUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    const API_ENDPOINT = '/api/links';

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();

        Sanctum::actingAs($this->user);
    }

    public function test_it_should_return_unauthorized_request()
    {
        $shortenedUrl = ShortenedUrlFactory::new()->create();

        $response = $this->getJson(
            self::API_ENDPOINT.'/'.$shortenedUrl->short_url
        );

        $response->assertStatus(403);
    }

    public function test_it_should_return_shortened_url()
    {
        $shortenedUrl = ShortenedUrlFactory::new(['user_id' => $this->user->id])->create();

        $this->getJson(
            self::API_ENDPOINT.'/'.$shortenedUrl->short_url
        )->assertStatus(200);
    }

    public function test_it_should_return_not_found()
    {
        $this->getJson(
            self::API_ENDPOINT.'/'.Str::random(10)
        )->assertStatus(404);
    }

    public function test_it_should_return_unauthorized_request_with_invalid_short_domain()
    {
        $this->getJson(
            self::API_ENDPOINT.'/example.com/'.Str::random(10)
        )->assertStatus(403);
    }
}
