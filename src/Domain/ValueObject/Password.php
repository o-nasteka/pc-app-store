<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class Password
{
    private string $hashedValue;

    private function __construct(string $hashedValue)
    {
        $this->hashedValue = $hashedValue;
    }

    public static function fromPlainText(string $plainText): self
    {
        if (strlen($plainText) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters long');
        }

        return new self(password_hash($plainText, PASSWORD_ARGON2ID));
    }

    public static function fromHash(string $hashedValue): self
    {
        return new self($hashedValue);
    }

    public function verify(string $plainText): bool
    {
        return password_verify($plainText, $this->hashedValue);
    }

    public function getHashedValue(): string
    {
        return $this->hashedValue;
    }

    public function needsRehash(): bool
    {
        return password_needs_rehash($this->hashedValue, PASSWORD_ARGON2ID);
    }
}
