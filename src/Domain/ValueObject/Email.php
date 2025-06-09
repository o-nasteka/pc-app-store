<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $lowercased = strtolower(trim($value));

        if (!filter_var($lowercased, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format: {$value}");
        }

        $this->value = $lowercased;
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
