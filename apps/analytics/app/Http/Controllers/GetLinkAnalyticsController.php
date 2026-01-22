<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAnalyticsRequest;
use App\Services\AnalyticsService;

class GetLinkAnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    public function __invoke(GetAnalyticsRequest $request, string $shortcode)
    {
        $params = $request->validated();

        $analytics = $this->analyticsService->getLinkAnalytics($shortcode, $params);

        return response()->json($analytics);
    }
}
