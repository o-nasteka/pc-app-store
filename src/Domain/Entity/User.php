<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserRole;

final class User
{
    private UserId $id;
    private Email $email;
    private Password $password;
    private string $name;
    private UserRole $role;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $lastLoginAt;

    public function __construct(
        UserId $id,
        Email $email,
        Password $password,
        string $name,
        UserRole $role,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->lastLoginAt = null;
    }

    public static function create(
        UserId $id,
        Email $email,
        Password $password,
        string $name,
        UserRole $role
    ): self {
        return new self(
            $id,
            $email,
            $password,
            $name,
            $role,
            new \DateTimeImmutable()
        );
    }

    public function updateLastLogin(): void
    {
        $this->lastLoginAt = new \DateTimeImmutable();
    }

    public function changePassword(Password $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role->equals(UserRole::admin());
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }
}
