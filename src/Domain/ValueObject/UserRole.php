<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class UserRole
{
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    private const VALID_ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_ROLES, true)) {
            throw new \InvalidArgumentException("Invalid role: {$value}");
        }

        $this->value = $value;
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
