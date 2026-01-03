<?php

namespace App\Repositories;

use App\DTOs\Link\CreateShortenedUrlDTO;
use App\DTOs\Link\ListShortenedUrlDTO;
use App\Models\ShortenedUrl;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShortenedUrlRepository
{
    public function create(CreateShortenedUrlDTO $dto, string $shortCode): ShortenedUrl
    {
        return ShortenedUrl::create([
            'shortcode' => $shortCode,
            'url' => $dto->url,
            'user_id' => $dto->userId,
            'title' => $dto->title,
        ]);
    }

    public function getPaginatedList(ListShortenedUrlDTO $dto): LengthAwarePaginator
    {
        return ShortenedUrl::where('user_id', $dto->userId)
            ->orderByDesc('id')
            ->paginate($dto->limit);
    }

    public function getByShortCode(string $code): ?ShortenedUrl
    {
        return ShortenedUrl::where('shortcode', $code)->first();
    }
}
