<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    /**
     * Save user to repository
     *
     * @param User $user
     * @throws \RuntimeException
     */
    public function save(User $user): void;

    /**
     * Find user by ID
     *
     * @param UserId $id
     * @return User|null
     * @throws \RuntimeException
     */
    public function findById(UserId $id): ?User;

    /**
     * Find user by email
     *
     * @param Email $email
     * @return User|null
     * @throws \RuntimeException
     */
    public function findByEmail(Email $email): ?User;

    /**
     * Get all users
     *
     * @return User[]
     * @throws \RuntimeException
     */
    public function findAll(): array;

    /**
     * Delete user from repository
     *
     * @param User $user
     * @throws \RuntimeException
     */
    public function delete(User $user): void;

    /**
     * Check if a user exists by email
     *
     * @param Email $email
     * @return bool
     * @throws \RuntimeException
     */
    public function existsByEmail(Email $email): bool;
}
