<?php

namespace App\Http\Controllers\Api\Link;

use App\Http\Controllers\Controller;
use App\Models\ShortenedUrl;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeleteShortenedUrlController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ShortenedUrl $shortenedUrl)
    {
        $this->authorize('manage', $shortenedUrl);

        $shortenedUrl->delete();

        return response()->noContent();
    }
}
