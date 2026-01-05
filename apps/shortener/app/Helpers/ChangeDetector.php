<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class ChangeDetector
{
    public static function detect(Model $model, array $data): array
    {
        $changes = [];

        foreach ($data as $field => $newValue) {
            $oldValue = $model->{$field};

            if (! static::areEqual($oldValue, $newValue)) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    public static function areEqual(mixed $oldValue, mixed $newValue): bool
    {
        if ($oldValue === null && $newValue === null) {
            return true;
        }

        if (is_bool($oldValue) || is_bool($newValue)) {
            return filter_var($oldValue, FILTER_VALIDATE_BOOLEAN) === filter_var($newValue, FILTER_VALIDATE_BOOLEAN);
        }

        if (is_array($oldValue) && is_array($newValue)) {
            return $oldValue === $newValue;
        }

        // Loose comparision for everything else
        return $oldValue == $newValue;
    }
}
