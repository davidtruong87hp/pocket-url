<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAnalyticsRequest;
use App\Services\AnalyticsService;

class GetUserAnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    public function __invoke(GetAnalyticsRequest $request, string $userId)
    {
        $params = $request->validated();

        $analytics = $this->analyticsService->getUserAnalytics((int) $userId, $params);

        return response()->json($analytics);
    }
}
