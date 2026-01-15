<?php

namespace App\Http\Controllers\Api\Link;

use App\DTOs\Link\CreateShortenedUrlDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\CreateShortenedUrlRequest;
use App\Http\Resources\Link\ShortenedUrlResource;
use App\Services\ShortenedUrlService;

class CreateShortenedUrlController extends Controller
{
    public function __construct(
        private ShortenedUrlService $shortenedUrlService
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateShortenedUrlRequest $request)
    {
        $validated = $request->validated();
        $dto = CreateShortenedUrlDTO::fromArray(array_merge(
            $validated,
            ['userId' => $request->user()->id]
        ));

        $shortenedUrl = $this->shortenedUrlService->create($dto);

        return new ShortenedUrlResource($shortenedUrl);
    }
}
