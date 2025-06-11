<?php

declare(strict_types=1);

namespace App\Application\UseCase\Activity;

use App\Application\DTO\Activity\GetStatisticsRequest;
use App\Application\DTO\Activity\GetStatisticsResponse;
use App\Domain\Repository\ActivityRepositoryInterface;

final class GetStatisticsUseCase
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function execute(GetStatisticsRequest $request): GetStatisticsResponse
    {
        // TODO: Implement logic to fetch statistics based on the request filters
        // and return a GetStatisticsResponse
        throw new \RuntimeException('Not implemented yet');
    }
}
