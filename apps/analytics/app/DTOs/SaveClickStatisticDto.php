<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

class SaveClickStatisticDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $shortcode,
        public Carbon $date,
        public int $totalClicks,
        public int $uniqueClicks,
        public int $botClicks,
        public int $mobileClicks,
        public array $topCountries,
        public array $topCities,
        public array $topReferrers,
        public array $topDevices,
        public array $topBrowsers,
        public array $topPlatforms,
        public array $hourlyDistribution
    ) {}

    public function fromArray(array $data): self
    {
        return new self(
            shortcode: $data['shortcode'],
            date: Carbon::parse($data['date']),
            totalClicks: $data['total_clicks'],
            uniqueClicks: $data['unique_clicks'],
            botClicks: $data['bot_clicks'],
            mobileClicks: $data['mobile_clicks'],
            topCountries: $data['top_countries'],
            topCities: $data['top_cities'],
            topReferrers: $data['top_referrers'],
            topDevices: $data['top_devices'],
            topBrowsers: $data['top_browsers'],
            topPlatforms: $data['top_platforms'],
            hourlyDistribution: $data['hourly_distribution']
        );
    }
}
