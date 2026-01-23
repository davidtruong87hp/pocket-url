<?php

namespace App\Services\Analytics\Transformers\Traits;

use Illuminate\Support\Collection;

trait AggregatesJsonColumns
{
    public function aggregateJsonColumn(Collection $stats, string $column, string $labelField = 'name', string $valueField = 'count'): array
    {
        $aggregated = [];

        foreach ($stats as $stat) {
            $data = is_string($stat->$column) ? json_decode($stat->$column, true) : $stat->$column;

            if (is_array($data)) {
                foreach ($data as $item) {
                    $key = $item[$labelField] ?? 'Unknown';
                    $value = $item[$valueField] ?? 0;

                    if (! isset($aggregated[$key])) {
                        $aggregated[$key] = 0;
                    }
                    $aggregated[$key] += $value;
                }
            }
        }

        arsort($aggregated);

        return collect($aggregated)->map(function ($value, $label) {
            return ['label' => $label, 'value' => $value];
        })->values()->toArray();
    }
}
