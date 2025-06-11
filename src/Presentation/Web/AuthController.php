<?php

declare(strict_types=1);

namespace App\Presentation\Web;

use App\Application\UseCase\Auth\LoginUseCase;
use App\Application\UseCase\Auth\RegisterUseCase;
use App\Application\UseCase\Auth\LogoutUseCase;
use App\Application\DTO\Auth\LoginRequest;
use App\Application\DTO\Auth\RegisterRequest;
use App\Application\DTO\Auth\LogoutRequest;
use App\Domain\Exception\AuthenticationException;
use App\Domain\Exception\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

final class AuthController
{
    public function __construct(
        private Environment $twig,
        private LoginUseCase $loginUseCase,
        private RegisterUseCase $registerUseCase,
        private LogoutUseCase $logoutUseCase,
        private SessionInterface $session
    ) {}

    public function showLogin(): Response
    {
        if ($this->session->has('user')) {
            return new RedirectResponse('/');
        }

        return new Response($this->twig->render('auth/login.html.twig', [
            'error' => $this->session->getFlashBag()->get('error')
        ]));
    }

    public function login(Request $request): Response
    {
        error_log('DEBUG: Entered login method');
        try {
            $loginRequest = new LoginRequest(
                $request->request->get('email', ''),
                $request->request->get('password', ''),
                $request->getClientIp() ?? '127.0.0.1',
                $request->headers->get('User-Agent')
            );

            $response = $this->loginUseCase->execute($loginRequest);

            $this->session->set('user', [
                'id' => $response->userId,
                'email' => $response->email,
                'name' => $response->name,
                'role' => $response->role
            ]);

            return new RedirectResponse('/');
        } catch (AuthenticationException $e) {
            $this->session->getFlashBag()->add('error', 'Invalid email or password');
            return new RedirectResponse('/login');
        } catch (\Exception $e) {
            error_log('Login error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->session->getFlashBag()->add('error', 'An error occurred. Please try again.');
            return new RedirectResponse('/login');
        }
    }

    public function showRegister(): Response
    {
        if ($this->session->has('user')) {
            return new RedirectResponse('/');
        }

        return new Response($this->twig->render('auth/register.html.twig', [
            'error' => $this->session->getFlashBag()->get('error')
        ]));
    }

    public function register(Request $request): Response
    {
        try {
            $registerRequest = new RegisterRequest(
                $request->request->get('email', ''),
                $request->request->get('password', ''),
                $request->request->get('name', ''),
                $request->getClientIp() ?? '127.0.0.1',
                $request->headers->get('User-Agent')
            );

            $this->registerUseCase->execute($registerRequest);

            $this->session->getFlashBag()->add('success', 'Registration successful! Please login.');
            return new RedirectResponse('/login');
        } catch (UserAlreadyExistsException $e) {
            $this->session->getFlashBag()->add('error', 'User with this email already exists');
            return new RedirectResponse('/register');
        } catch (\InvalidArgumentException $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
            return new RedirectResponse('/register');
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', 'An error occurred. Please try again.');
            return new RedirectResponse('/register');
        }
    }

    public function logout(Request $request): Response
    {
        $user = $this->session->get('user');

        if ($user) {
            try {
                $logoutRequest = new LogoutRequest(
                    $user['id'],
                    $request->getClientIp() ?? '127.0.0.1',
                    $request->headers->get('User-Agent')
                );

                $this->logoutUseCase->execute($logoutRequest);
            } catch (\Exception $e) {
                // Log error but continue with logout
                error_log('Logout tracking failed: ' . $e->getMessage());
            }
        }

        $this->session->invalidate();
        return new RedirectResponse('/login');
    }
}
