<?php

namespace App\Models;

use App\Events\ShortenedUrlCreated;
use App\Events\ShortenedUrlDeleted;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ShortenedUrl extends Model
{
    protected $fillable = [
        'shortcode', 'url', 'user_id', 'title',
    ];

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => config('shortener.short_domain').'/'.$this->shortcode,
        );
    }

    protected static function booted(): void
    {
        static::created(function (ShortenedUrl $shortenedUrl) {
            event(new ShortenedUrlCreated($shortenedUrl));
        });

        static::deleted(function (ShortenedUrl $shortenedUrl) {
            event(new ShortenedUrlDeleted($shortenedUrl));
        });
    }
}
