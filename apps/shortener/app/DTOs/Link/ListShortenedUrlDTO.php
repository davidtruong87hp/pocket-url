<?php

namespace App\DTOs\Link;

class ListShortenedUrlDTO
{
    public function __construct(
        public readonly ?int $page,
        public readonly ?int $limit,
        public readonly ?int $userId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page: $data['page'] ?? 1,
            limit: $data['limit'] ?? 10,
            userId: $data['userId'] ?? null,
        );
    }
}
