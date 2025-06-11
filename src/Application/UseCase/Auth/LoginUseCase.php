<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth;

use App\Application\DTO\Auth\LoginRequest;
use App\Application\DTO\Auth\LoginResponse;
use App\Domain\Entity\Activity;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\ActivityType;
use App\Domain\Exception\AuthenticationException;

final class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function execute(LoginRequest $request): LoginResponse
    {
        // Validate email
        try {
            $email = new Email($request->email);
        } catch (\InvalidArgumentException $e) {
            throw new AuthenticationException('Invalid credentials');
        }

        // Find user by email
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new AuthenticationException('Invalid credentials');
        }

        // Verify password
        if (!$user->getPassword()->verify($request->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        // Update last login
        $user->updateLastLogin();
        $this->userRepository->save($user);

        // Log login activity
        $activity = Activity::create(
            ActivityId::generate(),
            $user->getId(),
            ActivityType::login(),
            null, // page
            null, // button
            $request->ipAddress,
            $request->userAgent
        );

        $this->activityRepository->save($activity);

        // Return response
        return new LoginResponse(
            $user->getId()->getValue(),
            $user->getEmail()->getValue(),
            $user->getName(),
            $user->getRole()->getValue()
        );
    }
}
