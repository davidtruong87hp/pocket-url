<?php

namespace App\Http\Controllers\Api\Link;

use App\Helpers\ChangeDetector;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\UpdateShortenedUrlRequest;
use App\Http\Resources\Link\ShortenedUrlResource;
use App\Models\ShortenedUrl;
use App\Services\ShortenedUrlService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UpdateShortenedUrlController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ShortenedUrlService $shortenedUrlService) {}

    public function __invoke(UpdateShortenedUrlRequest $request, ShortenedUrl $shortenedUrl)
    {
        $this->authorize('manage', $shortenedUrl);

        $validated = $request->validated();
        $changes = ChangeDetector::detect($shortenedUrl, $validated);
        $shortenedUrl = $this->shortenedUrlService->update($shortenedUrl, $validated);

        return (new ShortenedUrlResource($shortenedUrl))->additional([
            'message' => 'Shortened URL updated successfully.',
            'changed_fields' => array_keys($changes),
        ]);
    }
}
