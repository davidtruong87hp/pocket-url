<?php

namespace App\Services;

use App\Models\ShortenedUrl;
use App\Models\UrlEditHistory;

class UrlEditHistoryService
{
    public function save(ShortenedUrl $shortenedUrl, array $changes): UrlEditHistory
    {
        return UrlEditHistory::create([
            'shortened_url_id' => $shortenedUrl->id,
            'changes' => $changes,
        ]);
    }
}
