<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

final class LogoutRequest
{
    public function __construct(
        public readonly string $userId,
        public readonly string $ipAddress,
        public readonly ?string $userAgent = null
    ) {}
}
