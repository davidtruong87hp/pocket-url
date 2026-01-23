<?php

namespace Tests\Unit\Services\Analytics\Transformers;

use App\Models\ClickStatistic;
use App\Services\Analytics\Transformers\SummaryTransformer;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SummaryTransformerTest extends TestCase
{
    private SummaryTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new SummaryTransformer;
    }

    public function test_it_transforms_summary(): void
    {
        $stats = collect([
            new ClickStatistic([
                'total_clicks' => 100,
                'unique_clicks' => 50,
                'mobile_clicks' => 25,
            ]),
            new ClickStatistic([
                'total_clicks' => 50,
                'unique_clicks' => 25,
                'mobile_clicks' => 12,
            ]),
        ]);

        $dateRange = [
            'start' => Carbon::parse('2026-01-16'),
            'end' => Carbon::parse('2026-01-17'),
        ];

        $result = $this->transformer->transform($stats, $dateRange);

        $this->assertEquals([
            'totalEngagements' => 150,
            'uniqueClicks' => 75,
            'mobileClicks' => 37,
            'dateRange' => [
                'start' => '2026-01-16T00:00:00+00:00',
                'end' => '2026-01-17T00:00:00+00:00',
            ],
        ], $result);
    }
}
