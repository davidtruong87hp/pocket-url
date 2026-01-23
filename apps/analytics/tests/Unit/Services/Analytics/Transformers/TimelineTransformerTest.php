<?php

namespace Tests\Unit\Services\Analytics\Transformers;

use App\Models\ClickStatistic;
use App\Services\Analytics\Transformers\TimelineTransformer;
use Tests\TestCase;

class TimelineTransformerTest extends TestCase
{
    private TimelineTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new TimelineTransformer;
    }

    public function test_it_transforms_timeline(): void
    {
        $stats = collect([
            new ClickStatistic([
                'total_clicks' => 100,
                'date' => '2026-01-16',
            ]),
            new ClickStatistic([
                'total_clicks' => 50,
                'date' => '2026-01-17',
            ]),
            new ClickStatistic([
                'total_clicks' => 75,
                'date' => '2026-01-18',
            ]),
        ]);

        $result = $this->transformer->transform($stats);

        $this->assertArrayIsEqualToArrayIgnoringListOfKeys([
            'label' => '01/16',
            'engagements' => 100,
        ], $result[0], ['date']);

        $this->assertArrayIsEqualToArrayIgnoringListOfKeys([
            'label' => '01/17',
            'engagements' => 50,
        ], $result[1], ['date']);

        $this->assertArrayIsEqualToArrayIgnoringListOfKeys([
            'label' => '01/18',
            'engagements' => 75,
        ], $result[2], ['date']);
    }
}
