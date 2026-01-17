<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeoLocationService
{
    private const CACHE_TTL = 86400; // 24 hours

    private const IP_API_URL = 'http://ip-api.com/json/';

    public function lookup(?string $ipAddress): array
    {
        if (empty($ipAddress) || $this->isPrivateIp($ipAddress)) {
            return $this->getDefaultGeoData();
        }

        $cacheKey = "geo:ip:$ipAddress";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($ipAddress) {
            try {
                /** @var \Illuminate\Http\Client\Response */
                $response = Http::timeout(5)->get(self::IP_API_URL.$ipAddress, [
                    'fields' => 'status,country,countryCode,region,city,lat,lon',
                ]);

                $data = $response->json();

                if ($data['status'] !== 'success') {
                    return $this->getDefaultGeoData();
                }

                return [
                    'country_code' => $data['countryCode'] ?? null,
                    'country_name' => $data['country'] ?? null,
                    'region' => $data['region'] ?? null,
                    'city' => $data['city'] ?? null,
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                ];
            } catch (Throwable $e) {
                Log::warning('GeoLocation lookup failed', [
                    'ip' => $ipAddress,
                    'error' => $e->getMessage(),
                ]);

                return $this->getDefaultGeoData();
            }
        });
    }

    private function isPrivateIp(string $ip): bool
    {
        // Check for localhost
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return true;
        }

        // Check for private IP ranges
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            'fc00::/7', // IPv6 unique local addresses
        ];

        foreach ($privateRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    private function ipInRange(string $ip, string $range): bool
    {
        try {
            [$subnet, $mask] = explode('/', $range);

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ip_long = ip2long($ip);
                $subnet_long = ip2long($subnet);
                $mask_long = -1 << (32 - (int) $mask);

                return ($ip_long & $mask_long) === ($subnet_long & $mask_long);
            }

            // Simple IPv6 prefix check (can be enhanced)
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                return str_starts_with($ip, substr($subnet, 0, 4));
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getDefaultGeoData(): array
    {
        return [
            'country_code' => null,
            'country_name' => null,
            'region' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
        ];
    }
}
