<?php

namespace App\Services;

use App\DTOs\CreateLinkClickDto;
use App\Repositories\LinkClickRepository;

class LinkClickService
{
    public function __construct(
        private LinkClickRepository $linkClickRepository
    ) {}

    public function create(CreateLinkClickDto $data)
    {
        return $this->linkClickRepository->create($data);
    }
}
