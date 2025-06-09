<?php

declare(strict_types=1);

namespace App\Config;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

final class Bootstrap
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->container->build();
    }

    public function handleRequest(): Response
    {
        $request = Request::createFromGlobals();

        // Initialize session
        $session = new Session();
        if (!$session->isStarted()) {
            $session->start();
        }
        $request->setSession($session);

        try {
            // Get routes
            $routes = Routes::getRoutes();

            // Create request context
            $context = new RequestContext();
            $context->fromRequest($request);

            // Match route
            $matcher = new UrlMatcher($routes, $context);
            $parameters = $matcher->match($request->getPathInfo());

            // Add route parameters to request
            $request->attributes->add($parameters);

            // Get controller from route
            $controllerName = $parameters['_controller'];

            // Parse controller string (e.g., "App\Presentation\Web\HomeController::index")
            if (strpos($controllerName, '::') !== false) {
                [$controllerClass, $controllerMethod] = explode('::', $controllerName);

                // Get controller instance from container
                $controllerInstance = $this->container->get($controllerClass);
                $controller = [$controllerInstance, $controllerMethod];
            } else {
                throw new \RuntimeException('Invalid controller format');
            }

            // Resolve arguments
            $argumentResolver = new ArgumentResolver();
            $arguments = $argumentResolver->getArguments($request, $controller);

            // Call controller
            $response = call_user_func_array($controller, $arguments);

            if (!$response instanceof Response) {
                throw new \LogicException('Controller must return a Response object');
            }

            return $response;

        } catch (ResourceNotFoundException $e) {
            return new Response('Page not found', 404);
        } catch (\Exception $e) {
            // Log error
            $logger = $this->container->get('logger');
            $logger->error($e->getMessage(), ['exception' => $e]);

            if ($_ENV['APP_DEBUG'] === 'true') {
                $content = sprintf(
                    '<h1>Error</h1><p>%s</p><p>File: %s:%d</p><pre>%s</pre>',
                    htmlspecialchars($e->getMessage()),
                    htmlspecialchars($e->getFile()),
                    $e->getLine(),
                    htmlspecialchars($e->getTraceAsString())
                );
                return new Response($content, 500);
            }

            return new Response('Internal Server Error', 500);
        }
    }
}
