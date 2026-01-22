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
            'ip_address' => $data->ipAddress,
            'user_agent' => $data->userAgent,
            'referrer_domain' => $data->referrerDomain,
            'referrer_url' => $data->referrerUrl,
            'is_bot' => $data->isBot,
            'is_mobile' => $data->isMobile,
            'clicked_at' => $data->clickedAt,
            'raw_data' => $data->rawData,
            'country' => $data->country,
            'country_name' => $data->countryName,
            'region' => $data->region,
            'city' => $data->city,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'device_type' => $data->deviceType,
            'device_brand' => $data->deviceBrand,
            'device_model' => $data->deviceModel,
            'os_name' => $data->osName,
            'os_version' => $data->osVersion,
            'browser_name' => $data->browser,
            'browser_version' => $data->browserVersion,
            'user_id' => $data->userId,
        ]);
    }
}
