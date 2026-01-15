<?php

namespace App\Repositories;

use App\DTOs\CreateLinkClickDto;
use App\Models\LinkClick;

class LinkClickRepository
{
    public function create(CreateLinkClickDto $data)
    {
        return LinkClick::create([
            'shortcode' => $data->shortcode,
            'original_url' => $data->originalUrl,
            'ip_address' => $data->ipAddress,
            'user_agent' => $data->userAgent,
            'referrer_domain' => $data->referrerDomain,
            'referrer_url' => $data->referrerUrl,
            'is_bot' => $data->isBot,
            'is_mobile' => $data->isMobile,
            'clicked_at' => $data->clickedAt,
            'raw_data' => $data->rawData,
        ]);
    }
}
