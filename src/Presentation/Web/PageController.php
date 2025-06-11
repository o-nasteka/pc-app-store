<?php

declare(strict_types=1);

namespace App\Presentation\Web;

use App\Application\UseCase\Activity\TrackPageViewUseCase;
use App\Application\DTO\Activity\TrackPageViewRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

final class PageController
{
    public function __construct(
        private Environment $twig,
        private TrackPageViewUseCase $trackPageViewUseCase,
        private SessionInterface $session
    ) {}

    public function showPageA(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $trackRequest = new TrackPageViewRequest(
                $user['id'],
                'page_a',
                $request->getClientIp() ?? '127.0.0.1',
                $request->headers->get('User-Agent')
            );
            $this->trackPageViewUseCase->execute($trackRequest);
        } catch (\Exception $e) {
            // Log error but continue showing the page
            error_log('Failed to track page view: ' . $e->getMessage());
        }

        return new Response($this->twig->render('pages/page_a.html.twig'));
    }

    public function showPageB(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $trackRequest = new TrackPageViewRequest(
                $user['id'],
                'page_b',
                $request->getClientIp() ?? '127.0.0.1',
                $request->headers->get('User-Agent')
            );
            $this->trackPageViewUseCase->execute($trackRequest);
        } catch (\Exception $e) {
            // Log error but continue showing the page
            error_log('Failed to track page view: ' . $e->getMessage());
        }

        return new Response($this->twig->render('pages/page_b.html.twig'));
    }

    public function downloadSample(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $filePath = __DIR__ . '/../../../public/downloads/sample.exe';
        if (!file_exists($filePath)) {
            return new Response('File not found', Response::HTTP_NOT_FOUND);
        }

        $response = new Response(file_get_contents($filePath));
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="sample.exe"');
        $response->headers->set('Content-Length', filesize($filePath));

        return $response;
    }
}
