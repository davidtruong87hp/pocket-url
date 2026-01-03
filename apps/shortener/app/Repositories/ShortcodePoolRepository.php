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
        $result = DB::select('
            SELECT * FROM shortcode_pool
            ORDER BY created_at ASC
            LIMIT 1
            FOR UPDATE SKIP LOCKED
        ');

        return $result[0] ?? null;
    }

    public function delete(string $shortcode): void
    {
        DB::table('shortcode_pool')->where('shortcode', $shortcode)->delete();
    }
}
