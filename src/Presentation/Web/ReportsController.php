<?php

declare(strict_types=1);

namespace App\Presentation\Web;

use App\Domain\Repository\ActivityRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

final class ReportsController
{
    public function __construct(
        private Environment $twig,
        private SessionInterface $session,
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function show(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user || $user['role'] !== 'admin') {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $startDate = $request->query->get('startDate') ? new \DateTimeImmutable($request->query->get('startDate')) : (new \DateTimeImmutable())->modify('-7 days');
        $endDate = $request->query->get('endDate') ? new \DateTimeImmutable($request->query->get('endDate')) : new \DateTimeImmutable();
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = max(1, (int)$request->query->get('perPage', 10));
        $offset = ($page - 1) * $perPage;

        $reportData = $this->activityRepository->getReportData($startDate, $endDate, $perPage, $offset);
        $chartData = $reportData['chart'] ?? [];
        $total = $this->activityRepository->getReportRowCount($startDate, $endDate);
        $totalPages = (int)ceil($total / $perPage);

        $dates = array_column($chartData, 'date');
        $pageAViews = array_column($chartData, 'page_a_views');
        $pageBViews = array_column($chartData, 'page_b_views');
        $buyCowClicks = array_column($chartData, 'buy_cow_clicks');
        $downloadClicks = array_column($chartData, 'download_clicks');

        return new Response($this->twig->render('admin/reports.html.twig', [
            'reports' => $chartData,
            'dates' => $dates,
            'pageAViews' => $pageAViews,
            'pageBViews' => $pageBViews,
            'buyCowClicks' => $buyCowClicks,
            'downloadClicks' => $downloadClicks,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'total' => $total
        ]));
    }
}
