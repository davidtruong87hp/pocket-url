<?php

namespace App\Http\Controllers\Api\Link;

use App\Http\Controllers\Controller;
use App\Http\Resources\Link\ShortenedUrlResource;
use App\Models\ShortenedUrl;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ViewShortenedUrlController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ShortenedUrl $shortenedUrl)
    {
        $this->authorize('manage', $shortenedUrl);

        return new ShortenedUrlResource($shortenedUrl);
    }
}
