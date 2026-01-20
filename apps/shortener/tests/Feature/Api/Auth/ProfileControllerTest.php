<?php

namespace Tests\Feature\Api\Auth;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public const API_ENDPOINT = '/api/profile';

    public function test_profile_success()
    {
        Sanctum::actingAs(UserFactory::new()->create());

        $this->getJson(self::API_ENDPOINT)->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);
    }

    public function test_profile_failed_unauthenticated()
    {
        $this->getJson(self::API_ENDPOINT)->assertUnauthorized();
    }
}
