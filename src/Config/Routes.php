<?php

declare(strict_types=1);

namespace App\Config;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

final class Routes
{
    public static function getRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        // Public routes (no authentication required)
        $routes->add('login', new Route('/login', [
            '_controller' => 'App\\Presentation\\Web\\AuthController::showLogin'
        ], [], [], '', [], ['GET']));

        $routes->add('login_post', new Route('/login', [
            '_controller' => 'App\\Presentation\\Web\\AuthController::login'
        ], [], [], '', [], ['POST']));

        $routes->add('register', new Route('/register', [
            '_controller' => 'App\\Presentation\\Web\\AuthController::showRegister'
        ], [], [], '', [], ['GET']));

        $routes->add('register_post', new Route('/register', [
            '_controller' => 'App\\Presentation\\Web\\AuthController::register'
        ], [], [], '', [], ['POST']));

        // Protected routes (authentication required)
        $routes->add('home', new Route('/', [
            '_controller' => 'App\\Presentation\\Web\\HomeController::index'
        ], [], [], '', [], ['GET']));

        $routes->add('logout', new Route('/logout', [
            '_controller' => 'App\\Presentation\\Web\\AuthController::logout'
        ], [], [], '', [], ['GET', 'POST']));

        $routes->add('page_a', new Route('/page-a', [
            '_controller' => 'App\\Presentation\\Web\\PageController::showPageA'
        ], [], [], '', [], ['GET']));

        $routes->add('page_b', new Route('/page-b', [
            '_controller' => 'App\\Presentation\\Web\\PageController::showPageB'
        ], [], [], '', [], ['GET']));

        // Admin routes
        $routes->add('statistics', new Route('/statistics', [
            '_controller' => 'App\\Presentation\\Web\\StatisticsController::index'
        ], [], [], '', [], ['GET']));

        $routes->add('reports', new Route('/reports', [
            '_controller' => 'App\\Presentation\\Web\\ReportsController::index'
        ], [], [], '', [], ['GET']));

        // API routes
        $routes->add('api_track_click', new Route('/api/activity/track-click', [
            '_controller' => 'App\\Presentation\\Api\\ActivityController::trackButtonClick'
        ], [], [], '', [], ['POST']));

        $routes->add('api_statistics', new Route('/api/statistics', [
            '_controller' => 'App\\Presentation\\Api\\ActivityController::getStatistics'
        ], [], [], '', [], ['GET']));

        $routes->add('api_report', new Route('/api/report', [
            '_controller' => 'App\\Presentation\\Api\\ActivityController::getReport'
        ], [], [], '', [], ['GET']));

        $routes->add('api_download', new Route('/api/download', [
            '_controller' => 'App\\Presentation\\Api\\ActivityController::downloadFile'
        ], [], [], '', [], ['GET']));

        $routes->add('api_users', new Route('/api/users', [
            '_controller' => 'App\\Presentation\\Api\\UserController::list'
        ], [], [], '', [], ['GET']));

        $routes->add('api_current_user', new Route('/api/user/current', [
            '_controller' => 'App\\Presentation\\Api\\UserController::current'
        ], [], [], '', [], ['GET']));

        return $routes;
    }
}
