<?php

namespace App\Services\Analytics\Transformers;

use App\Services\Analytics\Transformers\Traits\AggregatesJsonColumns;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class StatsCardsTransformer
{
    use AggregatesJsonColumns;

    public function transform(Collection $stats, array $dateRange): array
    {
        $totalEngagements = $stats->sum('total_clicks');
        $topDate = $stats->sortByDesc('total_clicks')->first();

        $countries = $this->aggregateJsonColumn($stats, 'top_countries');
        $referrers = $this->aggregateJsonColumn($stats, 'top_referrers', 'domain');

        return [
            [
                'title' => 'Total Engagements',
                'value' => (string) $totalEngagements,
                'subtitle' => $dateRange['start']->format('M d').' - '.$dateRange['end']->format('M d, Y'),
            ],
            [
                'title' => 'Top Date',
                'value' => (string) ($topDate->total_clicks ?? 0),
                'subtitle' => $topDate ? Carbon::parse($topDate->date)->format('F d, Y') : 'N/A',
            ],
            [
                'title' => 'Top Location',
                'value' => $countries[0]['label'] ?? 'N/A',
                'subtitle' => isset($countries[0]) ? $countries[0]['value'].' engagements' : '',
            ],
            [
                'title' => 'Top Referrer',
                'value' => $referrers[0]['label'] ?? 'N/A',
                'subtitle' => isset($referrers[0]) ? $referrers[0]['value'].' clicks' : '',
            ],
        ];
    }
}
