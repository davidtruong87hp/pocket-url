<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlEditHistory extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'shortened_url_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];
}
