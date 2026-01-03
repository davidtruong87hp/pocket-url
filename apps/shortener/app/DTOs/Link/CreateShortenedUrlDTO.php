<?php

namespace App\DTOs\Link;

class CreateShortenedUrlDTO
{
    public function __construct(
        public readonly string $url,
        public readonly int $userId,
        public readonly ?string $title,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            userId: $data['userId'],
            title: $data['title'] ?? null,
        );
    }
}
