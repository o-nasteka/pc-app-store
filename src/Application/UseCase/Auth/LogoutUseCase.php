<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth;

use App\Application\DTO\Auth\LogoutRequest;
use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\Entity\Activity;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\ActivityType;

final class LogoutUseCase
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function execute(LogoutRequest $request): void
    {
        // Log activity
        $activity = Activity::create(
            ActivityId::generate(),
            UserId::fromString($request->userId),
            ActivityType::logout(),
            [],
            $request->ipAddress,
            $request->userAgent
        );

        $this->activityRepository->save($activity);
    }
}
