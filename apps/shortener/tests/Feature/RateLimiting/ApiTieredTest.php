<?php

namespace Tests\Feature\RateLimiting;

use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTieredTest extends TestCase
{
    protected const ROUTE_TEST = '/test-rate-limit';

    protected function setUp(): void
    {
        parent::setUp();

        // Create test route
        Route::get(self::ROUTE_TEST, function () {
            return response()->json(['sucess' => true]);
        })->middleware('throttle:api-tiered');
    }

    public function test_rate_limiting_works_for_unauthenticated_user()
    {
        // Make 5 requests
        for ($i = 0; $i < 5; $i++) {
            $response = $this->getJson(self::ROUTE_TEST);
            $response->assertStatus(200);
        }

        // 6th request should be rate limited
        $response = $this->getJson(self::ROUTE_TEST);
        $response->assertStatus(429);
    }

    public function test_minute_rate_limiting_resets()
    {
        // Hit limit
        for ($i = 0; $i < 6; $i++) {
            $this->getJson(self::ROUTE_TEST);
        }

        $this->travel(1)->minutes();

        // Should be able to make another request
        $response = $this->getJson(self::ROUTE_TEST);
        $response->assertStatus(200);
    }

    public function test_rate_limiting_works_every_minute_for_authenticated_user()
    {
        Sanctum::actingAs(UserFactory::new()->create());

        // Make 10 requests
        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson(self::ROUTE_TEST);
            $response->assertStatus(200);
        }

        // 11th request should be rate limited
        $response = $this->getJson(self::ROUTE_TEST);
        $response->assertStatus(429);
        $response->assertJson(['limit_type' => 'every_minute']);
    }

    public function test_rate_limiting_works_every_hour_for_authenticated_user()
    {
        Sanctum::actingAs(UserFactory::new()->create());

        // Make 100 requests
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $response = $this->getJson(self::ROUTE_TEST);
                $response->assertStatus(200);
            }

            $this->travel(1)->minutes();
        }

        // 101st request should be rate limited
        $response = $this->getJson(self::ROUTE_TEST);
        $response->assertStatus(429);
        $response->assertJson(['limit_type' => 'hourly']);
    }
}
