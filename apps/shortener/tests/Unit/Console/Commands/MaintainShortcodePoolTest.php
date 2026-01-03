<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\MaintainShortcodePool;
use App\Services\ShortcodePoolManager;
use Mockery\MockInterface;
use Tests\TestCase;

class MaintainShortcodePoolTest extends TestCase
{
    public function test_it_should_not_do_anything_when_pool_is_healthy(): void
    {
        $this->mock(ShortcodePoolManager::class, function (MockInterface $mock) {
            $mock->expects('getStats')->once()->andReturn([
                'current_size' => 5000,
                'target_size' => 10000,
                'min_size' => 1000,
                'status' => 'healthy',
                'percent_full' => 50,
                'needs_refill' => false,
            ]);
        });

        $this->artisan(MaintainShortcodePool::class)
            ->expectsOutput('Pool is healthy.')
            ->assertExitCode(0);
    }

    public function test_it_should_refill_pool(): void
    {
        $this->mock(ShortcodePoolManager::class, function (MockInterface $mock) {
            $mock->expects('getStats')->once()->andReturn([
                'current_size' => 500,
                'target_size' => 10000,
                'min_size' => 1000,
                'status' => 'low',
                'percent_full' => 5,
                'needs_refill' => true,
            ]);

            $mock->expects('refill')->once()->with(9500)->andReturnArg(0);
        });

        $this->artisan(MaintainShortcodePool::class)
            ->expectsOutput('Refilled 9500 shortcodes.')
            ->assertExitCode(0);
    }
}
