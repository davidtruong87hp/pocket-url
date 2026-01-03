<?php

namespace Tests\Unit\Services;

use App\Repositories\ShortcodePoolRepository;
use App\Services\ShortcodePoolManager;
use Mockery\MockInterface;
use Tests\TestCase;

class ShortcodePoolManagerTest extends TestCase
{
    public function test_it_will_not_refill_pool_when_not_needed(): void
    {
        $this->mock(ShortcodePoolRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getPoolSize')->once()->andReturn(10000);
        });

        $service = $this->app->make(ShortcodePoolManager::class);
        $service->checkAndRefillPool();

        $this->assertTrue(true);
    }

    public function test_it_will_refill_pool_when_needed(): void
    {
        $this->mock(ShortcodePoolRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getPoolSize')->once()->andReturn(500);
            $mock->shouldReceive('insertBatch')->times(10)->andReturnUsing(function ($batch) {
                return count($batch);
            });
        });

        $service = $this->app->make(ShortcodePoolManager::class);
        $service->checkAndRefillPool();

        $this->assertTrue(true);
    }

    public function test_get_pool_stats(): void
    {
        $this->mock(ShortcodePoolRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getPoolSize')->once()->andReturn(500);
        });

        $service = $this->app->make(ShortcodePoolManager::class);
        $stats = $service->getStats();

        $this->assertArrayHasKey('current_size', $stats);
        $this->assertArrayHasKey('target_size', $stats);
        $this->assertArrayHasKey('min_size', $stats);
        $this->assertArrayHasKey('percent_full', $stats);
        $this->assertArrayHasKey('status', $stats);
        $this->assertArrayHasKey('needs_refill', $stats);
    }
}
