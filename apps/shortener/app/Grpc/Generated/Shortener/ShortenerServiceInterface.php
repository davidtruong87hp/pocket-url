<?php

namespace Shortener;

use Spiral\RoadRunner\GRPC;

interface ShortenerServiceInterface extends GRPC\ServiceInterface
{
    const NAME = 'shortener.ShortenerService';

    public function ResolveShortcode(GRPC\ContextInterface $ctx, ResolveShortcodeRequest $in): ResolveShortcodeResponse;
}
