<?php

namespace App\Http\Controllers\Api\Link;

use App\Http\Controllers\Controller;
use App\Http\Resources\Link\ShortenedUrlResource;
use App\Models\ShortenedUrl;
use App\Services\External\AnalyticsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ViewShortenedUrlController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ShortenedUrl $shortenedUrl)
    {
        $this->authorize('manage', $shortenedUrl);

        $stats = app(AnalyticsService::class)->getLinkAnalytics($shortenedUrl->shortcode);

        return (new ShortenedUrlResource($shortenedUrl))->additional([
            'stats' => $stats,
        ]);
    }
}
