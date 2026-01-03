<?php

namespace App\Models;

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
}
