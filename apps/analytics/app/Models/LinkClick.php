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
        'country',
        'country_name',
        'city',
        'region',
        'latitude',
        'longitude',
        'device_type',
        'device_brand',
        'device_model',
        'os_name',
        'os_version',
        'browser_name',
        'browser_version',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'user_id',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'is_mobile' => 'boolean',
        'clicked_at' => 'datetime',
        'raw_data' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
