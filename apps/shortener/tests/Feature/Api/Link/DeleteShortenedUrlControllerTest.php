<?php

namespace Tests\Feature\Api\Link;

use Database\Factories\ShortenedUrlFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteShortenedUrlControllerTest extends TestCase
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

    public function test_delete_shortened_url_failed_with_invalid_url()
    {
        $this->deleteJson(self::API_ENDPOINT.'/invalid-url')
            ->assertStatus(404);
    }

    public function test_delete_shortened_url_success()
    {
        $shortenedUrl = ShortenedUrlFactory::new(['user_id' => $this->user->id])->create();

        $this->deleteJson(self::API_ENDPOINT.'/'.$shortenedUrl->short_url)
            ->assertNoContent();
    }

    public function test_delete_shortened_url_failed_with_unauthorized()
    {
        $shortenedUrl = ShortenedUrlFactory::new()->create();

        $this->deleteJson(self::API_ENDPOINT.'/'.$shortenedUrl->short_url)
            ->assertStatus(403);
    }
}
