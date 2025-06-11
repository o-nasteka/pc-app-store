<?php

declare(strict_types=1);

namespace App\Application\UseCase\Activity;

use App\Application\DTO\Activity\TrackPageViewRequest;
use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\Entity\Activity;
use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\ActivityType;
use App\Domain\ValueObject\UserId;

final class TrackPageViewUseCase
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function execute(TrackPageViewRequest $request): void
    {
        $activity = Activity::create(
            ActivityId::generate(),
            UserId::fromString($request->userId),
            ActivityType::viewPage(),
            $request->page,
            null,
            $request->ipAddress,
            $request->userAgent
        );
        $this->activityRepository->save($activity);
    }
}
