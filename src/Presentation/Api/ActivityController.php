<?php

declare(strict_types=1);

namespace App\Presentation\Api;

use App\Application\UseCase\Activity\TrackButtonClickUseCase;
use App\Application\DTO\Activity\TrackButtonClickRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ActivityController
{
    public function __construct(
        private TrackButtonClickUseCase $trackButtonClickUseCase,
        private SessionInterface $session
    ) {}

    public function track(Request $request): Response
    {
        $user = $this->session->get('user');
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $data = json_decode($request->getContent(), true);
            if (!$data || !isset($data['type']) || !isset($data['button']) || !isset($data['page'])) {
                return new Response('Invalid request data', Response::HTTP_BAD_REQUEST);
            }

            $trackRequest = new TrackButtonClickRequest(
                $user['id'],
                $data['button'],
                $data['page'],
                $request->getClientIp() ?? '127.0.0.1',
                $request->headers->get('User-Agent')
            );

            $this->trackButtonClickUseCase->execute($trackRequest);

            return new Response('Activity tracked successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            error_log('Failed to track activity: ' . $e->getMessage());
            return new Response('An error occurred while tracking activity', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
