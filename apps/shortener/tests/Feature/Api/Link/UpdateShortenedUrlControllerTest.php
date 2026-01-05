<?php

namespace Tests\Feature\Api\Link;

use Database\Factories\ShortenedUrlFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateShortenedUrlControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

        $response = $this->putJson(
            self::API_ENDPOINT.'/'.$shortenedUrl->short_url,
            []
        );

        $response->assertStatus(403);
    }

    public function test_it_should_update_shortened_url_with_only_url()
    {
        $shortenedUrl = ShortenedUrlFactory::new(['user_id' => $this->user->id])->create();

        $newUrl = $this->faker->url();

        $this->putJson(
            self::API_ENDPOINT.'/'.$shortenedUrl->short_url,
            ['url' => $newUrl]
        )->assertStatus(200)
            ->assertJsonFragment(['original_url' => $newUrl])
            ->assertJsonFragment(['changed_fields' => ['url']]);

        $this->assertDatabaseCount('url_edit_histories', 1);
        $this->assertDatabaseHas('url_edit_histories', [
            'shortened_url_id' => $shortenedUrl->id,
            'changes->url->old' => $shortenedUrl->url,
            'changes->url->new' => $newUrl,
        ]);
    }

    public function test_it_should_update_shortened_url_with_only_title()
    {
        $shortenedUrl = ShortenedUrlFactory::new(['user_id' => $this->user->id])->create();

        $newTitle = $this->faker->sentence();

        $this->putJson(
            self::API_ENDPOINT.'/'.$shortenedUrl->short_url,
            ['title' => $newTitle]
        )->assertStatus(200)
            ->assertJsonFragment(['title' => $newTitle])
            ->assertJsonFragment(['original_url' => $shortenedUrl->url])
            ->assertJsonFragment(['changed_fields' => ['title']]);

        $this->assertDatabaseCount('url_edit_histories', 1);
        $this->assertDatabaseHas('url_edit_histories', [
            'shortened_url_id' => $shortenedUrl->id,
            'changes->title->old' => $shortenedUrl->title,
            'changes->title->new' => $newTitle,
        ]);
    }

    public function test_it_should_update_shortened_url_with_url_and_title()
    {
        $shortenedUrl = ShortenedUrlFactory::new(['user_id' => $this->user->id])->create();

        $newUrl = $this->faker->url();
        $newTitle = $this->faker->sentence();

        $this->putJson(
            self::API_ENDPOINT.'/'.$shortenedUrl->short_url,
            ['url' => $newUrl, 'title' => $newTitle]
        )->assertStatus(200)
            ->assertJsonFragment(['original_url' => $newUrl])
            ->assertJsonFragment(['title' => $newTitle])
            ->assertJsonFragment(['changed_fields' => ['url', 'title']]);

        $this->assertDatabaseCount('url_edit_histories', 1);
        $this->assertDatabaseHas('url_edit_histories', [
            'shortened_url_id' => $shortenedUrl->id,
            'changes->url->old' => $shortenedUrl->url,
            'changes->url->new' => $newUrl,
            'changes->title->old' => $shortenedUrl->title,
            'changes->title->new' => $newTitle,
        ]);
    }
}
