<?php

namespace App\DTOs;

class CreateLinkClickDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $shortcode,
        public ?string $ipAddress,
        public ?string $userAgent,
        public ?string $referrerDomain,
        public ?string $referrerUrl,
        public bool $isBot,
        public bool $isMobile,
        public string $clickedAt,
        public ?array $rawData,
        public ?string $country,
        public ?string $countryName,
        public ?string $region,
        public ?string $city,
        public ?string $latitude,
        public ?string $longitude,
        public ?string $deviceType,
        public ?string $deviceBrand,
        public ?string $deviceModel,
        public ?string $osName,
        public ?string $osVersion,
        public ?string $browser,
        public ?string $browserVersion,
        public int $userId
    ) {}

    /**
     * Create a new class instance from an array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            shortcode: $data['shortcode'],
            ipAddress: $data['ip_address'],
            userAgent: $data['user_agent'],
            referrerDomain: $data['referrer_domain'],
            referrerUrl: $data['referrer_url'],
            isBot: $data['is_bot'],
            isMobile: $data['is_mobile'],
            clickedAt: $data['clicked_at'],
            rawData: $data['raw_data'] ?? null,
            country: $data['country'] ?? null,
            countryName: $data['country_name'] ?? null,
            region: $data['region'] ?? null,
            city: $data['city'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            deviceType: $data['device_type'] ?? null,
            deviceBrand: $data['device_brand'] ?? null,
            deviceModel: $data['device_model'] ?? null,
            osName: $data['os_name'] ?? null,
            osVersion: $data['os_version'] ?? null,
            browser: $data['browser_name'] ?? null,
            browserVersion: $data['browser_version'] ?? null,
            userId: $data['user_id'] ?? 0,
        );
    }
}
