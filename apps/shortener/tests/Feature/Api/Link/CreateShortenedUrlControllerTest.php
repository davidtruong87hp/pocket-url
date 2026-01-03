<?php

namespace Tests\Feature\Api\Link;

use App\Repositories\ShortcodePoolRepository;
use Database\Factories\ShortcodePoolFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateShortenedUrlControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const API_ENDPOINT = '/api/links';

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(UserFactory::new()->create());
    }

    public function test_create_shortened_url_failed_without_required_url()
    {
        $this->postJson(self::API_ENDPOINT, ['url' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors('url');
    }

    public function test_create_shortened_url_failed_with_invalid_url()
    {
        $this->postJson(self::API_ENDPOINT, ['url' => 'invalid url'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('url');
    }

    public function test_create_shortened_url_failed_when_shortcode_pool_is_empty()
    {
        $spy = $this->spy(ShortcodePoolRepository::class);

        $this->postJson(self::API_ENDPOINT, ['url' => $this->faker->url()])
            ->assertStatus(500);

        $spy->shouldHaveReceived('claimShortCode');
    }

    public function test_create_shortened_url_success()
    {
        ShortcodePoolFactory::new(['shortcode' => 'abcDEF'])->create();

        $this->postJson(self::API_ENDPOINT, ['url' => $this->faker->url()])
            ->assertStatus(201)
            ->assertJsonFragment(['shortcode' => 'abcDEF']);
    }
}
