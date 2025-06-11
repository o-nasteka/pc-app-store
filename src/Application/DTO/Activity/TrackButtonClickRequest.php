<?php

declare(strict_types=1);

namespace App\Application\DTO\Activity;

final class TrackButtonClickRequest
{
    public function __construct(
        public readonly string $userId,
        public readonly string $button,
        public readonly string $page,
        public readonly string $ipAddress,
        public readonly ?string $userAgent
    ) {}
}
