<?php

namespace App\Services;

use App\DTOs\Link\CreateShortenedUrlDTO;
use App\DTOs\Link\ListShortenedUrlDTO;
use App\Events\ShortenedUrlUpdated;
use App\Helpers\ChangeDetector;
use App\Models\ShortenedUrl;
use App\Repositories\ShortenedUrlRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ShortenedUrlService
{
    public function __construct(
        private ShortcodeService $shortcodeService,
        private ShortenedUrlRepository $shortenedUrlRepository,
        private UrlEditHistoryService $urlEditHistoryService
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

    public function update(ShortenedUrl $shortenedUrl, array $data): ShortenedUrl
    {
        return DB::transaction(function () use ($shortenedUrl, $data) {
            $changes = ChangeDetector::detect($shortenedUrl, $data);

            if (! empty($changes)) {
                $this->urlEditHistoryService->save($shortenedUrl, $changes);
            }

            $shortenedUrl->update($data);

            if ($changes && ! empty($changes['url'])) {
                event(new ShortenedUrlUpdated($shortenedUrl));
            }

            return $shortenedUrl->refresh();
        });
    }
}
