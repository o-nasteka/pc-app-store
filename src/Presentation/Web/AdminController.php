<?php

declare(strict_types=1);

namespace App\Presentation\Web;

use App\Application\UseCase\Activity\GetStatisticsUseCase;
use App\Application\UseCase\Activity\GetReportUseCase;
use App\Application\DTO\Activity\GetStatisticsRequest;
use App\Application\DTO\Activity\GetReportRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

final class AdminController
{
    public function __construct(
        private Environment $twig,
        private GetStatisticsUseCase $getStatisticsUseCase,
        private GetReportUseCase $getReportUseCase,
        private SessionInterface $session
    ) {}

    public function showStatistics(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user || $user['role'] !== 'admin') {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $statisticsRequest = new GetStatisticsRequest(
                $request->query->get('date'),
                $request->query->get('user'),
                $request->query->get('action')
            );

            $response = $this->getStatisticsUseCase->execute($statisticsRequest);

            return new Response($this->twig->render('admin/statistics.html.twig', [
                'activities' => $response->activities,
                'users' => $response->users,
                'date' => $request->query->get('date'),
                'selectedUser' => $request->query->get('user'),
                'selectedAction' => $request->query->get('action')
            ]));
        } catch (\Exception $e) {
            error_log('Failed to get statistics: ' . $e->getMessage());
            return new Response('An error occurred while fetching statistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showReports(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user || $user['role'] !== 'admin') {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $reportRequest = new GetReportRequest(
                $request->query->get('startDate'),
                $request->query->get('endDate')
            );

            $response = $this->getReportUseCase->execute($reportRequest);

            return new Response($this->twig->render('admin/reports.html.twig', [
                'reports' => $response->reports,
                'dates' => $response->dates,
                'pageAViews' => $response->pageAViews,
                'pageBViews' => $response->pageBViews,
                'buyCowClicks' => $response->buyCowClicks,
                'downloadClicks' => $response->downloadClicks,
                'startDate' => $request->query->get('startDate'),
                'endDate' => $request->query->get('endDate')
            ]));
        } catch (\Exception $e) {
            error_log('Failed to get reports: ' . $e->getMessage());
            return new Response('An error occurred while fetching reports', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 