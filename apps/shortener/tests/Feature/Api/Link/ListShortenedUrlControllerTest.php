<?php

namespace Tests\Feature\Api\Link;

use Database\Factories\ShortenedUrlFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListShortenedUrlControllerTest extends TestCase
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

    public function test_it_should_return_an_empty_list()
    {
        $this->getJson(self::API_ENDPOINT)
            ->assertStatus(200)
            ->assertJsonFragment(['data' => []]);
    }

    public function test_it_should_return_a_paginated_list()
    {
        ShortenedUrlFactory::new(['user_id' => $this->user->id])->count(15)->create();

        $this->getJson(self::API_ENDPOINT)
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.last_page', 2)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.total', 15);
    }

    public function test_it_should_return_a_paginated_list_for_a_given_page()
    {
        ShortenedUrlFactory::new(['user_id' => $this->user->id])->count(15)->create();

        $this->getJson(self::API_ENDPOINT.'?page=2')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.last_page', 2)
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.total', 15);
    }
}
