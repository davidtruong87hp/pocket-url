<?php

namespace App\Services\Analytics;

use Illuminate\Support\Carbon;

class DateRangeParser
{
    public function parse(array $params): array
    {
        $timezone = $params['timezone'] ?? 'UTC';
        $preset = $params['dateRange'] ?? null;
        $startDate = $params['startDate'] ?? null;
        $endDate = $params['endDate'] ?? null;
        $groupBy = $params['groupBy'] ?? null;

        if ($preset) {
            $dates = $this->parsePreset($preset, $timezone);
        } else {
            $dates = $this->parseCustom($startDate, $endDate, $timezone);
        }

        return [
            'start' => $dates['start'],
            'end' => $dates['end'],
            'group_by' => $groupBy,
            'timezone' => $timezone,
        ];
    }

    private function parsePreset(string $preset, string $timezone): array
    {
        $now = Carbon::now($timezone);

        return match ($preset) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'yesterday' => [
                'start' => $now->copy()->subDay()->startOfDay(),
                'end' => $now->copy()->subDay()->endOfDay(),
            ],
            'last_7_days' => [
                'start' => $now->copy()->subDays(6)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'last_30_days' => [
                'start' => $now->copy()->subDays(29)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ],
            'last_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end' => $now->copy()->subMonth()->endOfMonth(),
            ]
        };
    }

    private function parseCustom(?string $startDate, ?string $endDate, string $timezone): array
    {
        $now = Carbon::now($timezone);

        return [
            'start' => $startDate
                ? Carbon::parse($startDate, $timezone)
                : $now->copy()->subDays(6)->startOfDay(),
            'end' => $endDate
                ? Carbon::parse($endDate, $timezone)
                : $now->copy()->endOfDay(),
        ];
    }
}
