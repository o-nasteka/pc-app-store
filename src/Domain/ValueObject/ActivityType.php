<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class ActivityType
{
    private const LOGIN = 'login';
    private const LOGOUT = 'logout';
    private const REGISTRATION = 'registration';
    private const VIEW_PAGE = 'view_page';
    private const BUTTON_CLICK = 'button_click';

    private function __construct(private string $value)
    {
        if (!in_array($value, [
            self::LOGIN,
            self::LOGOUT,
            self::REGISTRATION,
            self::VIEW_PAGE,
            self::BUTTON_CLICK
        ])) {
            throw new \InvalidArgumentException('Invalid activity type');
        }
    }

    public static function login(): self
    {
        return new self(self::LOGIN);
    }

    public static function logout(): self
    {
        return new self(self::LOGOUT);
    }

    public static function registration(): self
    {
        return new self(self::REGISTRATION);
    }

    public static function viewPage(): self
    {
        return new self(self::VIEW_PAGE);
    }

    public static function buttonClick(): self
    {
        return new self(self::BUTTON_CLICK);
    }

    public static function fromString(string $type): self
    {
        return new self($type);
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
