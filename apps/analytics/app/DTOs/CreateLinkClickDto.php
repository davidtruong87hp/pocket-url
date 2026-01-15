<?php

namespace App\DTOs;

class CreateLinkClickDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $shortcode,
        public string $originalUrl,
        public ?string $ipAddress,
        public ?string $userAgent,
        public ?string $referrerDomain,
        public ?string $referrerUrl,
        public bool $isBot,
        public bool $isMobile,
        public string $clickedAt,
        public ?array $rawData
    ) {}

    /**
     * Create a new class instance from an array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            shortcode: $data['shortcode'],
            originalUrl: $data['original_url'],
            ipAddress: $data['ip_address'],
            userAgent: $data['user_agent'],
            referrerDomain: $data['referrer_domain'],
            referrerUrl: $data['referrer_url'],
            isBot: $data['is_bot'],
            isMobile: $data['is_mobile'],
            clickedAt: $data['clicked_at'],
            rawData: $data['raw_data'] ?? null,
        );
    }
}
