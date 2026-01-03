<?php

namespace App\Http\Controllers\Api\Link;

use App\Http\Controllers\Controller;
use App\Http\Requests\Link\UpdateShortenedUrlRequest;
use App\Http\Resources\Link\ShortenedUrlResource;
use App\Models\ShortenedUrl;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UpdateShortenedUrlController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(UpdateShortenedUrlRequest $request, ShortenedUrl $shortenedUrl)
    {
        $this->authorize('manage', $shortenedUrl);

        $validated = $request->validated();
        $changed = [];

        foreach ($validated as $field => $value) {
            if ($shortenedUrl->{$field} !== $value) {
                $changed[$field] = [
                    'old' => $shortenedUrl->{$field},
                    'new' => $value,
                ];
            }
        }

        $shortenedUrl->update($validated);

        return (new ShortenedUrlResource($shortenedUrl->refresh()))->additional([
            'message' => 'Shortened URL updated successfully.',
            'changed_fields' => array_keys($changed),
        ]);
    }
}
