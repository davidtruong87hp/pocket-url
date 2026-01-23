<?php

namespace App\Services\Analytics;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AnalyticsCacheManager
{
    public function generateKey(string $type, array $dateRange, array $params = []): string
    {
        $cacheParams = collect($params)
            ->except(['api_key', 'token'])
            ->sortKeys()
            ->toArray();

        return sprintf(
            'analytics:%s:%s:%s:%s',
            $type,
            $dateRange['start']->toDateString(),
            $dateRange['end']->toDateString(),
            md5(json_encode($cacheParams))
        );
    }

    public function calculateTTL(Carbon $endDate): int
    {
        $now = now();

        /*
        * Historical data (before today): cache for 26 hours.
        * This data never changes, so we can cache it longer
        */
        if ($endDate->isBefore($now->copy()->startOfDay())) {
            return 24 * 60 * 60; // 24 hours
        }

        // Today's data, this data is constantly changing
        if ($endDate->isToday()) {
            return 5 * 60; // 5 minutes
        }

        // Future dates or edge cases
        return 60 * 60; // 1 hour
    }

    public function remember(string $key, int $ttl, array $tags, callable $callback)
    {
        if ($this->supportsTagging()) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    public function flushTags(array $tags): void
    {
        if ($this->supportsTagging()) {
            Cache::tags($tags)->flush();
        } else {
            Cache::flush();
        }
    }

    public function forget(string $key)
    {
        Cache::forget($key);
    }

    private function supportsTagging(): bool
    {
        $driver = config('cache.default');

        return in_array($driver, ['redis', 'memcached']);
    }
}
