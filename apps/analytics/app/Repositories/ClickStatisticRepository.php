<?php

namespace App\Repositories;

use App\DTOs\SaveClickStatisticDto;
use App\Models\ClickStatistic;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClickStatisticRepository
{
    public function save(SaveClickStatisticDto $dto)
    {
        return ClickStatistic::query()->updateOrCreate([
            'shortcode' => $dto->shortcode,
            'date' => $dto->date->toDateString(),
        ], [
            'total_clicks' => $dto->totalClicks,
            'unique_clicks' => $dto->uniqueClicks,
            'bot_clicks' => $dto->botClicks,
            'mobile_clicks' => $dto->mobileClicks,
            'top_countries' => $dto->topCountries,
            'top_cities' => $dto->topCities,
            'top_referrers' => $dto->topReferrers,
            'top_devices' => $dto->topDevices,
            'top_browsers' => $dto->topBrowsers,
            'top_platforms' => $dto->topPlatforms,
            'hourly_distribution' => $dto->hourlyDistribution,
        ]);
    }

    public function getUniqueShortcodesByDate(Carbon $date): Collection
    {
        return DB::table('link_clicks')
            ->whereDate('clicked_at', $date)
            ->select('shortcode')
            ->distinct()
            ->pluck('shortcode');
    }

    public function getClicksByShortcodeAndDate(string $shortcode, Carbon $date): Builder
    {
        return DB::table('link_clicks')
            ->where('shortcode', $shortcode)
            ->whereDate('clicked_at', $date);
    }

    public function aggregateTotalClicksByDate(string $shortcode, Carbon $date)
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->select([
                DB::raw('COUNT(*) as total_clicks'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_clicks'),
                DB::raw('COUNT(CASE WHEN is_bot THEN 1 END) as bot_clicks'),
                DB::raw('COUNT(CASE WHEN is_mobile THEN 1 END) as mobile_clicks'),
            ])
            ->first();
    }

    public function aggregateByCountries(string $shortcode, Carbon $date): Collection
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->whereNotNull('country')
            ->groupBy('country', 'country_name')
            ->select([
                'country',
                'country_name',
                DB::raw('COUNT(*) as count'),
            ])
            ->get();
    }

    public function aggregateByCities(string $shortcode, Carbon $date): Collection
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->whereNotNull('city')
            ->groupBy('city', 'country_name')
            ->select([
                'city',
                'country_name',
                DB::raw('COUNT(*) as count'),
            ])
            ->get();
    }

    public function aggregateByReferrers(string $shortcode, Carbon $date): Collection
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->whereNotNull('referrer_domain')
            ->groupBy('referrer_domain')
            ->select([
                'referrer_domain',
                DB::raw('COUNT(*) as count'),
            ])
            ->get();
    }

    public function aggregateByDevices(string $shortcode, Carbon $date): Collection
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->select([
                'device_type',
                DB::raw('COUNT(*) as count'),
            ])
            ->get();
    }

    public function aggregateByBrowsers(string $shortcode, Carbon $date): Collection
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->whereNotNull('browser_name')
            ->groupBy('browser_name')
            ->select([
                'browser_name',
                DB::raw('COUNT(*) as count'),
            ])
            ->get();
    }

    public function aggregateByPlatforms(string $shortcode, Carbon $date): Collection
    {
        return $this->getClicksByShortcodeAndDate($shortcode, $date)
            ->whereNotNull('os_name')
            ->groupBy('os_name')
            ->select([
                'os_name',
                DB::raw('COUNT(*) as count'),
            ])
            ->get();
    }
}
