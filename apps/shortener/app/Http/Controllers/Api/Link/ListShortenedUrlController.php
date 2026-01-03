<?php

namespace App\Http\Controllers\Api\Link;

use App\DTOs\Link\ListShortenedUrlDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\ListShortenedUrlRequest;
use App\Http\Resources\Link\ShortenedUrlCollection;
use App\Services\ShortenedUrlService;

class ListShortenedUrlController extends Controller
{
    public function __construct(
        private ShortenedUrlService $shortenedUrlService
    ) {}

    public function __invoke(ListShortenedUrlRequest $request)
    {
        $validated = $request->validated();
        $dto = ListShortenedUrlDTO::fromArray(array_merge(
            $validated,
            ['userId' => $request->user()->id]
        ));

        $shortenedUrls = $this->shortenedUrlService->getPaginatedList($dto);

        return new ShortenedUrlCollection($shortenedUrls);
    }
}
