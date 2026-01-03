<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ShortcodePoolRepository
{
    public function getPoolSize(): int
    {
        return DB::table('shortcode_pool')->count();
    }

    public function insertBatch(array $batch): int
    {
        return DB::table('shortcode_pool')->insertOrIgnore($batch);
    }

    public function claimShortCode(): ?object
    {
        return DB::table('shortcode_pool')->lockForUpdate()
            ->limit(1)
            ->first();
    }

    public function delete(string $shortcode): void
    {
        DB::table('shortcode_pool')->where('shortcode', $shortcode)->delete();
    }
}
