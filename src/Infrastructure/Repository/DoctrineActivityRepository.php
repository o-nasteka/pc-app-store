<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\Entity\Activity;
use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\ActivityType;
use Doctrine\DBAL\Connection;

final class DoctrineActivityRepository implements ActivityRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(Activity $activity): void
    {
        $data = [
            'id' => $activity->getId()->getValue(),
            'user_id' => $activity->getUserId()->getValue(),
            'type' => $activity->getType()->getValue(),
            'metadata' => json_encode($activity->getMetadata()),
            'ip_address' => $activity->getIpAddress(),
            'user_agent' => $activity->getUserAgent(),
            'created_at' => $activity->getCreatedAt()->format('Y-m-d H:i:s')
        ];

        try {
            $this->connection->insert('activities', $data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to save activity: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findById(ActivityId $id): ?Activity
    {
        try {
            $data = $this->connection->fetchAssociative(
                'SELECT * FROM activities WHERE id = ?',
                [$id->getValue()]
            );

            return $data ? $this->hydrate($data) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find activity by ID: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findByUserId(UserId $userId): array
    {
        try {
            $data = $this->connection->fetchAllAssociative(
                'SELECT * FROM activities WHERE user_id = ? ORDER BY created_at DESC',
                [$userId->getValue()]
            );

            return array_map([$this, 'hydrate'], $data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find activities by user ID: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findByFilters(array $filters): array
    {
        try {
            $sql = 'SELECT a.*, u.name as user_name, u.email as user_email 
                    FROM activities a 
                    LEFT JOIN users u ON a.user_id = u.id 
                    WHERE 1=1';
            $params = [];
            $types = [];

            if (isset($filters['dateFrom']) && $filters['dateFrom'] instanceof \DateTimeImmutable) {
                $sql .= ' AND a.created_at >= ?';
                $params[] = $filters['dateFrom']->format('Y-m-d 00:00:00');
            }

            if (isset($filters['dateTo']) && $filters['dateTo'] instanceof \DateTimeImmutable) {
                $sql .= ' AND a.created_at <= ?';
                $params[] = $filters['dateTo']->format('Y-m-d 23:59:59');
            }

            if (isset($filters['userId']) && $filters['userId'] instanceof UserId) {
                $sql .= ' AND a.user_id = ?';
                $params[] = $filters['userId']->getValue();
            }

            if (isset($filters['type']) && $filters['type'] instanceof ActivityType) {
                $sql .= ' AND a.type = ?';
                $params[] = $filters['type']->getValue();
            }

            $sql .= ' ORDER BY a.created_at DESC';

            if (isset($filters['limit'])) {
                $sql .= ' LIMIT ' . (int)$filters['limit'];
            }

            $data = $this->connection->fetchAllAssociative($sql, $params);

            return array_map(function ($row) {
                $activity = $this->hydrate($row);

                // Add user info to result
                return [
                    'id' => $activity->getId()->getValue(),
                    'userId' => $activity->getUserId()->getValue(),
                    'type' => $activity->getType()->getValue(),
                    'metadata' => array_merge($activity->getMetadata(), [
                        'user_name' => $row['user_name'] ?? 'Unknown',
                        'user_email' => $row['user_email'] ?? 'Unknown'
                    ]),
                    'ipAddress' => $activity->getIpAddress(),
                    'userAgent' => $activity->getUserAgent(),
                    'createdAt' => $activity->getCreatedAt()->format('Y-m-d H:i:s')
                ];
            }, $data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find activities by filters: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getStatistics(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        try {
            $sql = 'SELECT 
                        type,
                        COUNT(*) as count,
                        DATE(created_at) as date
                    FROM activities
                    WHERE created_at BETWEEN ? AND ?
                    GROUP BY type, DATE(created_at)
                    ORDER BY date';

            return $this->connection->fetchAllAssociative(
                $sql,
                [
                    $startDate->format('Y-m-d 00:00:00'),
                    $endDate->format('Y-m-d 23:59:59')
                ]
            );
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to get statistics: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getReportData(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        try {
            // Get chart data
            $chartSql = "SELECT 
                            DATE(created_at) as date,
                            SUM(CASE 
                                WHEN type = 'view_page' AND JSON_EXTRACT(metadata, '$.page') = 'page_a' 
                                THEN 1 ELSE 0 
                            END) as page_a_views,
                            SUM(CASE 
                                WHEN type = 'view_page' AND JSON_EXTRACT(metadata, '$.page') = 'page_b' 
                                THEN 1 ELSE 0 
                            END) as page_b_views,
                            SUM(CASE 
                                WHEN type = 'button_click' AND JSON_EXTRACT(metadata, '$.button') = 'buy_cow' 
                                THEN 1 ELSE 0 
                            END) as buy_cow_clicks,
                            SUM(CASE 
                                WHEN type = 'button_click' AND JSON_EXTRACT(metadata, '$.button') = 'download' 
                                THEN 1 ELSE 0 
                            END) as download_clicks
                        FROM activities
                        WHERE created_at BETWEEN ? AND ?
                        GROUP BY DATE(created_at)
                        ORDER BY date";

            $chartData = $this->connection->fetchAllAssociative(
                $chartSql,
                [
                    $startDate->format('Y-m-d 00:00:00'),
                    $endDate->format('Y-m-d 23:59:59')
                ]
            );

            // Convert to integers
            $chartData = array_map(function ($row) {
                return [
                    'date' => $row['date'],
                    'page_a_views' => (int)$row['page_a_views'],
                    'page_b_views' => (int)$row['page_b_views'],
                    'buy_cow_clicks' => (int)$row['buy_cow_clicks'],
                    'download_clicks' => (int)$row['download_clicks']
                ];
            }, $chartData);

            return [
                'chart' => $chartData,
                'table' => $chartData // Same data for table
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to get report data: ' . $e->getMessage(), 0, $e);
        }
    }

    private function hydrate(array $data): Activity
    {
        return new Activity(
            ActivityId::fromString($data['id']),
            UserId::fromString($data['user_id']),
            new ActivityType($data['type']),
            json_decode($data['metadata'], true) ?? [],
            $data['ip_address'],
            $data['user_agent'],
            new \DateTimeImmutable($data['created_at'])
        );
    }
}
