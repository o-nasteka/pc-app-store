<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Activity;
use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\UserId;

interface ActivityRepositoryInterface
{
    /**
     * Save activity to repository
     */
    public function save(Activity $activity): void;

    /**
     * Find activity by ID
     */
    public function findById(ActivityId $id): ?Activity;

    /**
     * Find activities by user ID
     */
    public function findByUserId(UserId $userId): array;

    /**
     * Find activities by filters
     * @param array $filters Can contain: dateFrom, dateTo, userId, type
     * @return array
     */
    public function findByFilters(array $filters): array;

    /**
     * Get statistics for date range
     */
    public function getStatistics(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array;

    /**
     * Get report data for date range
     */
    public function getReportData(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array;
}
