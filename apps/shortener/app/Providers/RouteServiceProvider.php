<?php

namespace App\Providers;

use App\Helpers\ShortUrlParser;
use App\Repositories\ShortenedUrlRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::bind('shortUrl', function (string $value) {
            $pieces = ShortUrlParser::parse($value);
            $domain = $pieces['domain'];
            $code = $pieces['code'];

            if (! empty($domain) && $domain !== config('shortener.short_domain')) {
                abort(403, 'Invalid domain.');
            }

            $shortenedUrl = $this->app->make(ShortenedUrlRepository::class)->getByShortCode($code);

            if (! $shortenedUrl) {
                abort(404, 'The requested URL was not found.');
            }

            return $shortenedUrl;
        });
    }
}
