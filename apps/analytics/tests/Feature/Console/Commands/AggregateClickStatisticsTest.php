<?php

namespace Tests\Feature\Console\Commands;

use App\Helpers\LocationHelper;
use Database\Factories\LinkClickFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AggregateClickStatisticsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_aggregates_nothing_when_no_clicks(): void
    {
        $this->artisan('analytics:aggregate')
            ->assertOk()
            ->expectsOutput('No clicks found for this date.');
    }

    public function test_aggregates_click_statistics_concurrently(): void
    {
        $this->seedRealisticData();

        $this->artisan('analytics:aggregate')->assertOk();

        $this->assertGreaterThan(
            0,
            DB::table('click_statistics')->count()
        );
    }

    private function seedRealisticData(): void
    {
        $shortCodes = [];
        $targetDate = Carbon::yesterday();
        $countries = LocationHelper::getCodes();

        for ($i = 0; $i < 10; $i++) {
            $shortCodes[] = $this->faker->lexify('??????');
        }

        foreach ($shortCodes as $code) {
            foreach ($countries as $country) {
                $count = $this->faker->randomNumber(2, true);

                LinkClickFactory::new()
                    ->forShortCode($code)
                    ->onDate($targetDate)
                    ->fromCountry($country)
                    ->count(count: $count)->create();
            }
        }
    }
}
