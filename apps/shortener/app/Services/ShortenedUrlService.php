<?php

namespace App\Services;

use App\DTOs\Link\CreateShortenedUrlDTO;
use App\DTOs\Link\ListShortenedUrlDTO;
use App\Models\ShortenedUrl;
use App\Repositories\ShortenedUrlRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ShortenedUrlService
{
    public function __construct(
        private ShortcodeService $shortcodeService,
        private ShortenedUrlRepository $shortenedUrlRepository
    ) {}

    public function create(CreateShortenedUrlDTO $dto): ShortenedUrl
    {
        $shortCode = $this->shortcodeService->claimShortCode();

        return $this->shortenedUrlRepository->create($dto, $shortCode);
    }

    public function getPaginatedList(ListShortenedUrlDTO $dto): LengthAwarePaginator
    {
        return $this->shortenedUrlRepository->getPaginatedList($dto);
    }
}
