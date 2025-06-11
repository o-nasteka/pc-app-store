<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\UserRole;
use DateTimeImmutable;

final class User
{
    private ?DateTimeImmutable $lastLogin = null;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    private function __construct(
        private UserId $id,
        private Email $email,
        private Password $password,
        private string $name,
        private UserRole $role
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public static function create(
        Email $email,
        Password $password,
        string $name,
        UserRole $role
    ): self {
        return new self(
            UserId::generate(),
            $email,
            $password,
            $name,
            $role
        );
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

    public function getLastLogin(): ?DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function updateLastLogin(): void
    {
        $this->lastLogin = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
