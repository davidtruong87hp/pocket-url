<?php

namespace App\Helpers;

class ShortUrlParser
{
    public static function parse($url): array
    {
        $url = str_replace(['http://', 'https://'], '', $url);
        $pieces = explode('/', $url, 2);

        if (count($pieces) === 1) {
            $shortDomain = config('shortener.short_domain');
            $shortCode = $pieces[0] ?? null;
        } else {
            $shortDomain = $pieces[0] ?? null;
            $shortCode = $pieces[1] ?? null;
        }

        return [
            'domain' => $shortDomain,
            'code' => $shortCode,
        ];
    }
}
