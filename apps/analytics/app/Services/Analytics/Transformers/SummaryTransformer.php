<?php

namespace App\Services\Analytics\Transformers;

use Illuminate\Database\Eloquent\Collection;

class SummaryTransformer
{
    public function transform(Collection $stats, array $dateRange): array
    {
        return [
            'totalEngagements' => $stats->sum('total_clicks'),
            'uniqueClicks' => $stats->sum('unique_clicks'),
            'mobileClicks' => $stats->sum('mobile_clicks'),
            'dateRange' => [
                'start' => $dateRange['start']->toIso8601String(),
                'end' => $dateRange['end']->toIso8601String(),
            ],
        ];
    }
}
