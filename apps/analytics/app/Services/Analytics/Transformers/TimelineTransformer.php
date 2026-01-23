<?php

namespace App\Services\Analytics\Transformers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TimelineTransformer
{
    public function transform(Collection $stats): array
    {
        return $stats->sortBy('date')->map(function ($stat) {
            return [
                'date' => $stat->date,
                'label' => Carbon::parse($stat->date)->format('m/d'),
                'engagements' => $stat->total_clicks,
            ];
        })->values()->toArray();
    }
}
