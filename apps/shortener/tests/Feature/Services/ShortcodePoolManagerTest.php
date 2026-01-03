<?php

namespace Tests\Feature\Services;

use App\Services\ShortcodePoolManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortcodePoolManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_refill_pool_from_scratch(): void
    {
        $service = $this->app->make(ShortcodePoolManager::class);
        $service->checkAndRefillPool();

        $this->assertDatabaseCount('shortcode_pool', config('shortener.pool.target_size'));
    }

    public function test_refill_pool_with_specific_amount(): void
    {
        $service = $this->app->make(ShortcodePoolManager::class);
        $service->refill(100);

        $stats = $service->getStats();

        $this->assertEquals(100, $stats['current_size']);
        $this->assertEquals(true, $stats['needs_refill']);
        $this->assertEquals('low', $stats['status']);
    }
}
