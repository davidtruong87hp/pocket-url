<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClickStatistic extends Model
{
    protected $fillable = [
        'shortcode',
        'date',
        'total_clicks',
        'unique_clicks',
        'bot_clicks',
        'mobile_clicks',
        'top_countries',
        'top_cities',
        'top_referrers',
        'top_devices',
        'top_browsers',
        'top_platforms',
        'hourly_distribution',
    ];

    protected $casts = [
        'date' => 'date',
        'top_countries' => 'array',
        'top_cities' => 'array',
        'top_referrers' => 'array',
        'top_devices' => 'array',
        'top_browsers' => 'array',
        'top_platforms' => 'array',
        'hourly_distribution' => 'array',
    ];
}
