<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class UserAgentParserService
{
    private const CACHE_TTL = 3600;

    public function parse(string $userAgent): array
    {
        if (empty($userAgent)) {
            return $this->getDefaultData();
        }

        $cacheKey = 'ua:'.md5($userAgent);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userAgent) {
            $agent = new Agent;
            $agent->setUserAgent($userAgent);

            $platform = $agent->platform();
            $browser = $agent->browser();

            return [
                'isBot' => $agent->isRobot(),
                'isMobile' => $agent->isMobile(),
                'device_type' => $agent->deviceType(),
                'device_brand' => $agent->device(),
                'device_model' => null,
                'os_name' => $platform,
                'os_version' => $agent->version($platform),
                'browser_name' => $browser,
                'browser_version' => $agent->version($browser),
            ];
        });
    }

    private function getDefaultData(): array
    {
        return [
            'device_type' => null,
            'device_brand' => null,
            'device_model' => null,
            'os_name' => null,
            'os_version' => null,
            'browser_name' => null,
            'browser_version' => null,
            'is_bot' => false,
            'is_mobile' => false,
        ];
    }
}
