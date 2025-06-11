<?php

declare(strict_types=1);

namespace App\Presentation\Web;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

final class HomeController
{
    public function __construct(
        private Environment $twig,
        private SessionInterface $session
    ) {}

    public function index(): Response
    {
        $user = $this->session->get('user');

        if (!$user) {
            return new RedirectResponse('/login');
        }

        $html = $this->twig->render('home.html.twig', [
            'user' => $user
        ]);

        return new Response($html);
    }
}
