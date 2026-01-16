<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'shortcode',
        'ip_address',
        'user_agent',
        'referrer_domain',
        'referrer_url',
        'is_bot',
        'is_mobile',
        'raw_data',
        'clicked_at',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'is_mobile' => 'boolean',
        'clicked_at' => 'datetime',
        'raw_data' => 'array',
    ];
}
