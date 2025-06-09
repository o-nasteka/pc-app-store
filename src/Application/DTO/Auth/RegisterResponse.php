<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

final class RegisterResponse
{
    public function __construct(
        public readonly string $userId,
        public readonly string $email,
        public readonly string $name
    ) {}
}
