<?php

namespace Tests\Feature\Services;

use App\Exceptions\NoAvailableShortcodesException;
use App\Services\ShortcodeService;
use Database\Factories\ShortcodePoolFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortcodeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_claim_shortcode_failed_because_pool_is_empty(): void
    {
        $service = $this->app->make(ShortcodeService::class);

        $this->expectException(NoAvailableShortcodesException::class);
        $service->claimShortCode();
    }

    public function test_claim_shortcode_success(): void
    {
        ShortcodePoolFactory::new()->count(10)->create();

        $service = $this->app->make(ShortcodeService::class);
        $shortcode = $service->claimShortCode();

        $this->assertNotEmpty($shortcode);
        $this->assertDatabaseCount('shortcode_pool', 9);
    }
}
