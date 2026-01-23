<?php

namespace Tests\Unit\Services\Analytics\Transformers;

use App\Models\ClickStatistic;
use App\Services\Analytics\Transformers\DeviceBreakdownTransformer;
use Tests\TestCase;

class DeviceBreakdownTransformerTest extends TestCase
{
    private DeviceBreakdownTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new DeviceBreakdownTransformer;
    }

    public function test_it_transforms_device_breakdown()
    {
        $stats = collect([
            new ClickStatistic([
                'top_devices' => [
                    ['type' => 'Desktop', 'count' => 50],
                    ['type' => 'Mobile', 'count' => 30],
                ],
            ]),
            new ClickStatistic([
                'top_devices' => [
                    ['type' => 'Desktop', 'count' => 20],
                    ['type' => 'Tablet', 'count' => 10],
                ],
            ]),
        ]);

        $result = $this->transformer->transform($stats);

        $this->assertCount(3, $result);

        $desktop = collect($result)->firstWhere('label', 'Desktop');
        $this->assertEquals(70, $desktop['value']);
        $this->assertEquals(63.64, $desktop['percentage']);
    }
}
