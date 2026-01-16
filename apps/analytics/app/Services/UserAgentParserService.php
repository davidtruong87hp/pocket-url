<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class UserAgentParserService
{
    private const CACHE_TTL = 3600;

    private array $botPatterns = [
        'bot', 'crawler', 'spider', 'scraper', 'slurp', 'facebook',
        'google', 'bing', 'yahoo', 'duckduckgo', 'baidu', 'yandex',
    ];

    public function parse(string $userAgent): array
    {
        if (empty($userAgent)) {
            return [];
        }

        $cacheKey = 'ua:'.md5($userAgent);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userAgent) {
            return [
                'isBot' => $this->detectBot($userAgent),
                'isMobile' => $this->isMobile($userAgent),
            ];
        });
    }

    private function detectBot(string $ua): bool
    {
        $ua = strtolower($ua);

        foreach ($this->botPatterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function isMobile(string $ua): bool
    {
        return (bool) preg_match('/(mobile|ipod|iphone|android|blackberry|opera mini|iemobile)/i', $ua);
    }
}
