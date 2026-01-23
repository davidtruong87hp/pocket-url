<?php

namespace Tests\Unit\Services\Analytics\Transformers;

use App\Models\ClickStatistic;
use App\Services\Analytics\Transformers\LocationBreakdownTransformer;
use Tests\TestCase;

class LocationBreakdownTransformerTest extends TestCase
{
    private LocationBreakdownTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new LocationBreakdownTransformer;
    }

    public function test_it_transforms_location_breakdown(): void
    {
        $stats = collect([
            new ClickStatistic([
                'top_countries' => [
                    ['code' => 'US', 'name' => 'United States', 'count' => 50],
                    ['code' => 'GB', 'name' => 'United Kingdom', 'count' => 30],
                ],
                'top_cities' => [
                    ['city' => 'New York', 'country' => 'United States', 'count' => 25],
                    ['city' => 'Los Angeles', 'country' => 'United States', 'count' => 25],
                    ['city' => 'Manchester', 'country' => 'United Kingdom', 'count' => 15],
                    ['city' => 'Birmingham', 'country' => 'United Kingdom', 'count' => 10],
                    ['city' => 'London', 'country' => 'United Kingdom', 'count' => 5],
                ],
            ]),
        ]);

        $result = $this->transformer->transform($stats);

        $this->assertCount(2, $result['countries']);
        $this->assertCount(5, $result['cities']);

        $topCountries = $result['countries'];
        $topCities = $result['cities'];

        $this->assertEquals([
            [
                'rank' => 1,
                'location' => 'United States',
                'engagements' => 50,
                'percentage' => 62.5,
            ],
            [
                'rank' => 2,
                'location' => 'United Kingdom',
                'engagements' => 30,
                'percentage' => 37.5,
            ],
        ], $topCountries);

        $this->assertEquals([
            [
                'rank' => 1,
                'location' => 'New York',
                'engagements' => 25,
                'percentage' => 31.25,
            ],
            [
                'rank' => 2,
                'location' => 'Los Angeles',
                'engagements' => 25,
                'percentage' => 31.25,
            ],
            [
                'rank' => 3,
                'location' => 'Manchester',
                'engagements' => 15,
                'percentage' => 18.75,
            ],
            [
                'rank' => 4,
                'location' => 'Birmingham',
                'engagements' => 10,
                'percentage' => 12.5,
            ],
            [
                'rank' => 5,
                'location' => 'London',
                'engagements' => 5,
                'percentage' => 6.25,
            ],
        ], $topCities);
    }
}
