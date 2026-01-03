<?php

namespace App\Policies;

use App\Models\ShortenedUrl;
use App\Models\User;

class ShortenedUrlPolicy
{
    /**
     * Determine if user can manage (view/update/delete) the shortened url.
     */
    public function manage(User $user, ShortenedUrl $shortenedUrl)
    {
        return $user->id === $shortenedUrl->user_id;
    }
}
