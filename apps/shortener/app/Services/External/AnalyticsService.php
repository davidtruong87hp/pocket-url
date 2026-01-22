<?php

namespace App\Services\External;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AnalyticsService
{
    public function getLinkAnalytics(string $shortcode)
    {
        $apiKey = config('services.analytics.api_key');
        $apiEndpoint = config('services.analytics.endpoint');
        $url = "{$apiEndpoint}/api/links/{$shortcode}/analytics";

        /** @var Response */
        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
            'Accept' => 'application/json',
        ])->get($url);

        if ($response->failed()) {
            logger()->error("Failed to fetch analytics for {$shortcode} from {$url}: {$response->body()}");

            return [];
        }

        return $response->json();
    }
}
