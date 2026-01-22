<?php

namespace App\Jobs;

use App\DTOs\CreateLinkClickDto;
use App\Services\GeoLocationService;
use App\Services\LinkClickService;
use App\Services\UserAgentParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

use function Symfony\Component\Clock\now;

class ProcessClickEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [5, 10, 30];

    /**
     * Create a new job instance.
     */
    public function __construct(private array $clickData) {}

    /**
     * Execute the job.
     */
    public function handle(
        UserAgentParserService $userAgentParser,
        GeoLocationService $geoLocationService,
        LinkClickService $linkClickService
    ): void {
        try {
            $referrerUrl = $this->clickData['referer'] ?? null;
            $referrerData = $this->parseReferrer($referrerUrl);

            $deviceInfo = $userAgentParser->parse($this->clickData['userAgent']);

            $geoData = $geoLocationService->lookup($this->clickData['ip']);

            $createLinkClickDto = CreateLinkClickDto::fromArray([
                'shortcode' => $this->clickData['shortcode'],
                'ip_address' => $this->clickData['ip'],
                'user_agent' => $this->clickData['userAgent'],

                'referrer_domain' => $referrerData['domain'] ?? null,
                'referrer_url' => $referrerUrl,

                'is_bot' => $deviceInfo['isBot'] ?? false,
                'is_mobile' => $deviceInfo['isMobile'] ?? false,

                'raw_data' => $this->clickData,
                'clicked_at' => $this->clickData['timestamp'] ?? now(),

                // Device info
                'device_type' => $deviceInfo['device_type'] ?? null,
                'device_brand' => $deviceInfo['device_brand'] ?? null,
                'device_model' => $deviceInfo['device_model'] ?? null,
                'os_name' => $deviceInfo['os_name'] ?? null,
                'os_version' => $deviceInfo['os_version'] ?? null,
                'browser_name' => $deviceInfo['browser_name'] ?? null,
                'browser_version' => $deviceInfo['browser_version'] ?? null,

                // Geolocation
                'country' => $geoData['country_code'] ?? null,
                'country_name' => $geoData['country_name'] ?? null,
                'city' => $geoData['city'] ?? null,
                'region' => $geoData['region'] ?? null,
                'latitude' => $geoData['latitude'] ?? null,
                'longitude' => $geoData['longitude'] ?? null,

                'user_id' => (int) ($this->clickData['ownerId'] ?? ''),
            ]);

            $linkClickService->create($createLinkClickDto);

            Log::info('Click event processed successfully', [
                'shortcode' => $this->clickData['shortcode'],
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to process click event', [
                'error' => $e->getMessage(),
                'data' => $this->clickData,
            ]);

            throw $e;
        }
    }

    private function parseReferrer(?string $referrer): array
    {
        if (empty($referrer)) {
            return [];
        }

        try {
            $parsed = parse_url($referrer);

            return [
                'domain' => $parsed['host'] ?? null,
                'path' => $parsed['path'] ?? null,
            ];
        } catch (Throwable $e) {
            return [];
        }
    }
}
