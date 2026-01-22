<?php

namespace App\Services\Analytics\Transformers\Traits;

use Illuminate\Database\Eloquent\Collection;

trait AggregatesJsonColumns
{
    public function aggregateJsonColumn(Collection $stats, string $column): array
    {
        $aggregated = [];

        foreach ($stats as $stat) {
            $data = is_string($stat->$column) ? json_decode($stat->$column, true) : $stat->$column;

            if (is_array($data)) {
                foreach ($data as $item) {
                    $key = $item['label'] ?? $item['name'] ?? 'Unknown';
                    $value = $item['count'] ?? $item['value'] ?? 0;

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
