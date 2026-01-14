<?php

namespace App\Http\Controllers\Api\Link;

use App\Events\ShortenedUrlDeleted;
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

        event(new ShortenedUrlDeleted($shortenedUrl->shortcode));

        return response()->noContent();
    }
}
