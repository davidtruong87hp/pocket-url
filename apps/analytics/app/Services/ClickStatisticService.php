<?php

namespace App\Services;

use App\DTOs\SaveClickStatisticDto;
use App\Repositories\ClickStatisticRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ClickStatisticService
{
    public function __construct(
        private ClickStatisticRepository $clickStatisticRepository
    ) {}

    public function save(SaveClickStatisticDto $dto)
    {
        return $this->clickStatisticRepository->save($dto);
    }

    public function getUniqueShortcodesByDate(Carbon $date): Collection
    {
        return $this->clickStatisticRepository->getUniqueShortcodesByDate($date);
    }

    public function aggregateTotalClicks(string $shortcode, Carbon $date): array
    {
        $aggregated = $this->clickStatisticRepository->aggregateTotalClicksByDate($shortcode, $date);

        return [
            'total_clicks' => $aggregated->total_clicks,
            'unique_clicks' => $aggregated->unique_clicks,
            'bot_clicks' => $aggregated->bot_clicks,
            'mobile_clicks' => $aggregated->mobile_clicks,
        ];
    }

    public function aggregateTopCountries(string $shortcode, Carbon $date, int $limit = 10): array
    {
        return $this->clickStatisticRepository
            ->aggregateByCountries($shortcode, $date)
            ->map(fn ($group) => [
                'code' => $group->country,
                'name' => $group->country_name,
                'count' => $group->count,
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function aggregateTopCities(string $shortcode, Carbon $date, int $limit = 10): array
    {
        return $this->clickStatisticRepository
            ->aggregateByCities($shortcode, $date)
            ->map(fn ($group) => [
                'city' => $group->city,
                'country' => $group->country_name,
                'count' => $group->count,
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function aggregateTopReferrers(string $shortcode, Carbon $date, int $limit = 10): array
    {
        return $this->clickStatisticRepository
            ->aggregateByReferrers($shortcode, $date, $limit)
            ->map(fn ($group) => [
                'domain' => $group->referrer_domain,
                'count' => $group->count,
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function aggregateTopDevices(string $shortcode, Carbon $date, int $limit = 10): array
    {
        return $this->clickStatisticRepository
            ->aggregateByDevices($shortcode, $date)
            ->map(fn ($group) => [
                'type' => $group->device_type,
                'count' => $group->count,
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function aggregateTopBrowsers(string $shortcode, Carbon $date, int $limit = 10): array
    {
        return $this->clickStatisticRepository
            ->aggregateByBrowsers($shortcode, $date)
            ->map(fn ($group) => [
                'name' => $group->browser_name,
                'count' => $group->count,
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function aggregateTopPlatforms(string $shortcode, Carbon $date, int $limit = 10): array
    {
        return $this->clickStatisticRepository
            ->aggregateByPlatforms($shortcode, $date)
            ->map(fn ($group) => [
                'name' => $group->platform_name,
                'count' => $group->count,
            ])
            ->sortByDesc('count')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function aggregateHourlyDistribution(string $shortcode, Carbon $date): array
    {
        $hourlyDistribution = array_fill(0, 24, 0);

        $this->clickStatisticRepository
            ->getClicksByShortcodeAndDate($shortcode, $date)
            ->chunkById(1000, function (Collection $clicks) use (&$hourlyDistribution) {
                foreach ($clicks as $click) {
                    $hour = Carbon::parse($click->clicked_at)->hour;
                    $hourlyDistribution[$hour]++;
                }
            });

        return $hourlyDistribution;
    }
}
