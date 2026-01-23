<?php

namespace Tests\Unit\Services\Analytics\Transformers;

use App\Models\ClickStatistic;
use App\Services\Analytics\Transformers\ReferrerBreakdownTransformer;
use Tests\TestCase;

class ReferrerBreakdownTransformerTest extends TestCase
{
    private ReferrerBreakdownTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new ReferrerBreakdownTransformer;
    }

    public function test_it_transforms_referrer_breakdown(): void
    {
        $stats = collect([
            new ClickStatistic([
                'top_referrers' => [
                    ['domain' => 'google.com', 'count' => 50],
                    ['domain' => 'bing.com', 'count' => 30],
                ],
            ]),
            new ClickStatistic([
                'top_referrers' => [
                    ['domain' => 'bing.com', 'count' => 30],
                    ['domain' => 'yahoo.com', 'count' => 20],
                ],
            ]),
        ]);

        $result = $this->transformer->transform($stats);

        $this->assertEquals([
            ['label' => 'bing.com', 'value' => 60],
            ['label' => 'google.com', 'value' => 50],
            ['label' => 'yahoo.com', 'value' => 20],
        ], $result);
    }
}
