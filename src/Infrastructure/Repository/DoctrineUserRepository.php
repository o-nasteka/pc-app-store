<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserRole;
use Doctrine\DBAL\Connection;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(User $user): void
    {
        $data = [
            'id' => $user->getId()->getValue(),
            'email' => $user->getEmail()->getValue(),
            'password' => $user->getPassword()->getHashedValue(),
            'name' => $user->getName(),
            'role' => $user->getRole()->getValue(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'last_login_at' => $user->getLastLoginAt()?->format('Y-m-d H:i:s')
        ];

        try {
            // Check if user exists
            $existing = $this->connection->fetchAssociative(
                'SELECT id FROM users WHERE id = ?',
                [$user->getId()->getValue()]
            );

            if ($existing) {
                // Update existing user
                unset($data['created_at']); // Don't update created_at
                $this->connection->update('users', $data, ['id' => $user->getId()->getValue()]);
            } else {
                // Insert new user
                $this->connection->insert('users', $data);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to save user: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findById(UserId $id): ?User
    {
        try {
            $data = $this->connection->fetchAssociative(
                'SELECT * FROM users WHERE id = ?',
                [$id->getValue()]
            );

            return $data ? $this->hydrate($data) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find user by ID: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findByEmail(Email $email): ?User
    {
        try {
            $data = $this->connection->fetchAssociative(
                'SELECT * FROM users WHERE email = ?',
                [$email->getValue()]
            );

            return $data ? $this->hydrate($data) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find user by email: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findAll(): array
    {
        try {
            $data = $this->connection->fetchAllAssociative(
                'SELECT * FROM users ORDER BY created_at DESC'
            );

            return array_map([$this, 'hydrate'], $data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch all users: ' . $e->getMessage(), 0, $e);
        }
    }

    public function delete(User $user): void
    {
        try {
            $this->connection->delete('users', ['id' => $user->getId()->getValue()]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete user: ' . $e->getMessage(), 0, $e);
        }
    }

    private function hydrate(array $data): User
    {
        // Create user instance with reflection to set private properties
        $reflection = new \ReflectionClass(User::class);
        $user = $reflection->newInstanceWithoutConstructor();

        // Set properties using reflection
        $properties = [
            'id' => UserId::fromString($data['id']),
            'email' => new Email($data['email']),
            'password' => Password::fromHash($data['password']),
            'name' => $data['name'],
            'role' => new UserRole($data['role']),
            'createdAt' => new \DateTimeImmutable($data['created_at'])
        ];

        if ($data['last_login_at']) {
            $properties['lastLoginAt'] = new \DateTimeImmutable($data['last_login_at']);
        }

        foreach ($properties as $propertyName => $value) {
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($user, $value);
        }

        return $user;
    }
}
