<?php

declare(strict_types=1);

namespace App\Presentation\Web;

use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\ActivityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

final class StatisticsController
{
    public function __construct(
        private Environment $twig,
        private ActivityRepositoryInterface $activityRepository,
        private UserRepositoryInterface $userRepository,
        private SessionInterface $session
    ) {}

    public function show(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user || $user['role'] !== 'admin') {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $filters = [];
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $userId = $request->query->get('user');
        $action = $request->query->get('action');
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = max(1, (int)$request->query->get('perPage', 10));
        $offset = ($page - 1) * $perPage;

        if ($startDate) {
            $filters['dateFrom'] = new \DateTimeImmutable($startDate . ' 00:00:00');
        }
        if ($endDate) {
            $filters['dateTo'] = new \DateTimeImmutable($endDate . ' 23:59:59');
        }
        if ($userId) {
            $filters['userId'] = UserId::fromString($userId);
        }
        if ($action) {
            $filters['type'] = ActivityType::fromString($action);
        }
        $filters['limit'] = $perPage;
        $filters['offset'] = $offset;

        $activities = $this->activityRepository->findByFilters($filters);
        $total = $this->activityRepository->countByFilters($filters);
        $users = $this->userRepository->findAll();
        $totalPages = (int)ceil($total / $perPage);

        return new Response($this->twig->render('admin/statistics.html.twig', [
            'activities' => $activities,
            'users' => $users,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedUser' => $userId,
            'selectedAction' => $action,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'total' => $total
        ]));
    }
}
