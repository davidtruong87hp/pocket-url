<?php

namespace App\Services;

use App\Exceptions\NoAvailableShortcodesException;
use App\Repositories\ShortcodePoolRepository;
use Illuminate\Support\Facades\DB;

class ShortcodeService
{
    public function __construct(private ShortcodePoolRepository $poolRepository) {}

    public function claimShortCode(): string
    {
        return DB::transaction(function () {
            $record = $this->poolRepository->claimShortCode();

            if (empty($record)) {
                throw new NoAvailableShortcodesException('No available shortcodes in pool. Please refill.');
            }

            $this->poolRepository->delete($record->shortcode);

            return $record->shortcode;
        });
    }
}
