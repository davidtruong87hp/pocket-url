<?php

namespace Tests\Unit\Services;

use App\Exceptions\NoAvailableShortcodesException;
use App\Repositories\ShortcodePoolRepository;
use App\Services\ShortcodeService;
use Mockery\MockInterface;
use Tests\TestCase;

class ShortcodeServiceTest extends TestCase
{
    public function test_claim_shortcode_success(): void
    {
        $fakeShortcodeRecord = (object) [
            'shortcode' => 'fake-shortcode',
        ];

        $this->mock(ShortcodePoolRepository::class, function (MockInterface $mock) use ($fakeShortcodeRecord) {
            $mock->expects('claimShortCode')->once()->andReturn($fakeShortcodeRecord);
            $mock->expects('delete')->once()->with($fakeShortcodeRecord->shortcode);
        });

        $service = $this->app->make(ShortcodeService::class);

        $this->assertEquals($fakeShortcodeRecord->shortcode, $service->claimShortCode());
    }

    public function test_claim_shortcode_failure_because_pool_is_empty(): void
    {
        $this->mock(ShortcodePoolRepository::class, function (MockInterface $mock) {
            $mock->expects('claimShortCode')->once()->andReturnNull();
        });

        $service = $this->app->make(ShortcodeService::class);

        $this->expectException(NoAvailableShortcodesException::class);
        $service->claimShortCode();
    }
}
