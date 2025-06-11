<?php

declare(strict_types=1);

namespace App\Application\UseCase\Activity;

use App\Domain\Repository\ActivityRepositoryInterface;

final class GetReportUseCase
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    // Add your report logic here
}
