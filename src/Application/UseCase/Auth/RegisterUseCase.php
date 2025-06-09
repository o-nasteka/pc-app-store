<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth;

use App\Application\DTO\Auth\RegisterRequest;
use App\Application\DTO\Auth\RegisterResponse;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\UserRole;
use Ramsey\Uuid\Uuid;

final class RegisterUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(RegisterRequest $request): RegisterResponse
    {
        $email = new Email($request->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw new UserAlreadyExistsException("User with email already exists");
        }

        $user = new User(
            UserId::fromString(Uuid::uuid4()->toString()),
            $email,
            new Password($request->password),
            new UserRole('user'),
            new \DateTimeImmutable()
        );

        $this->userRepository->save($user);

        return new RegisterResponse(
            id: $user->getId()->getValue(),
            email: (string) $user->getEmail()
        );
    }
}
