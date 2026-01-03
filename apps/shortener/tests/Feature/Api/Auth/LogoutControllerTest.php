<?php

namespace Tests\Feature\Api\Auth;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public const API_ENDPOINT = '/api/logout';

    public function test_logout_success()
    {
        Sanctum::actingAs(UserFactory::new()->create());

        $this->postJson(self::API_ENDPOINT)->assertJsonStructure([
            'message',
        ]);
    }

    public function test_logout_unauthenticated()
    {
        $this->postJson(self::API_ENDPOINT)->assertUnauthorized();
    }
}
