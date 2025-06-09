<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class ActivityType
{
    public const TYPE_LOGIN = 'login';
    public const TYPE_LOGOUT = 'logout';
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_VIEW_PAGE = 'view_page';
    public const TYPE_BUTTON_CLICK = 'button_click';

    private const VALID_TYPES = [
        self::TYPE_LOGIN,
        self::TYPE_LOGOUT,
        self::TYPE_REGISTRATION,
        self::TYPE_VIEW_PAGE,
        self::TYPE_BUTTON_CLICK,
    ];

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid activity type: {$value}");
        }

        $this->value = $value;
    }

    public static function login(): self
    {
        return new self(self::TYPE_LOGIN);
    }

    public static function logout(): self
    {
        return new self(self::TYPE_LOGOUT);
    }

    public static function registration(): self
    {
        return new self(self::TYPE_REGISTRATION);
    }

    public static function viewPage(): self
    {
        return new self(self::TYPE_VIEW_PAGE);
    }

    public static function buttonClick(): self
    {
        return new self(self::TYPE_BUTTON_CLICK);
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
