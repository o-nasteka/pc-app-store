<?php

declare(strict_types=1);

namespace App\Config;

use App\Application\UseCase\Activity\GetReportUseCase;
use App\Application\UseCase\Activity\GetStatisticsUseCase;
use App\Application\UseCase\Activity\TrackButtonClickUseCase;
use App\Application\UseCase\Activity\TrackPageViewUseCase;
use App\Application\UseCase\Auth\LoginUseCase;
use App\Application\UseCase\Auth\LogoutUseCase;
use App\Application\UseCase\Auth\RegisterUseCase;
use App\Domain\Repository\ActivityRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Repository\DoctrineActivityRepository;
use App\Infrastructure\Repository\DoctrineUserRepository;
use App\Presentation\Api\ActivityController;
use App\Presentation\Api\UserController;
use App\Presentation\Web\AuthController;
use App\Presentation\Web\HomeController;
use App\Presentation\Web\PageController;
use App\Presentation\Web\ReportsController;
use App\Presentation\Web\StatisticsController;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class Container
{
    private array $services = [];
    private array $factories = [];

    public function build(): void
    {
        $this->registerCoreServices();
        $this->registerRepositories();
        $this->registerUseCases();
        $this->registerControllers();
    }

    private function registerCoreServices(): void
    {
        // Database Connection
        $this->factories[Connection::class] = function (): Connection {
            $connectionParams = [
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'] ?? 3306,
                'driver' => 'pdo_mysql',
                'charset' => 'utf8mb4',
            ];

            return DriverManager::getConnection($connectionParams);
        };

        // Logger
        $this->factories[LoggerInterface::class] = function (): LoggerInterface {
            $logger = new Logger('app');
            $logPath = $_ENV['LOG_PATH'] ?? dirname(__DIR__, 2) . '/var/logs/app.log';
            $logLevel = $_ENV['LOG_LEVEL'] ?? 'debug';

            $logger->pushHandler(new StreamHandler($logPath, Logger::toMonologLevel($logLevel)));

            return $logger;
        };

        $this->services['logger'] = $this->get(LoggerInterface::class);

        // Session
        $this->factories[SessionInterface::class] = function (): SessionInterface {
            $sessionPath = $_ENV['SESSION_PATH'] ?? dirname(__DIR__, 2) . '/var/sessions';

            if (!is_dir($sessionPath)) {
                mkdir($sessionPath, 0777, true);
            }

            $storage = new NativeSessionStorage([
                'cookie_lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 3600),
                'cookie_httponly' => true,
                'cookie_secure' => $_ENV['APP_ENV'] === 'prod',
                'save_path' => $sessionPath,
            ]);

            return new Session($storage);
        };

        // Twig
        $this->factories[Environment::class] = function (): Environment {
            $loader = new FilesystemLoader(dirname(__DIR__, 2) . '/templates');

            $twig = new Environment($loader, [
                'cache' => $_ENV['APP_ENV'] === 'prod' ? dirname(__DIR__, 2) . '/var/cache/twig' : false,
                'debug' => $_ENV['APP_DEBUG'] === 'true',
                'auto_reload' => true,
            ]);

            // Add globals
            $twig->addGlobal('app', [
                'session' => $this->get(SessionInterface::class),
                'environment' => $_ENV['APP_ENV'],
            ]);

            return $twig;
        };
    }

    private function registerRepositories(): void
    {
        // User Repository
        $this->factories[UserRepositoryInterface::class] = function (): UserRepositoryInterface {
            return new DoctrineUserRepository($this->get(Connection::class));
        };

        // Activity Repository
        $this->factories[ActivityRepositoryInterface::class] = function (): ActivityRepositoryInterface {
            return new DoctrineActivityRepository($this->get(Connection::class));
        };
    }

    private function registerUseCases(): void
    {
        // Auth Use Cases
        $this->factories[LoginUseCase::class] = function (): LoginUseCase {
            return new LoginUseCase(
                $this->get(UserRepositoryInterface::class),
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        $this->factories[RegisterUseCase::class] = function (): RegisterUseCase {
            return new RegisterUseCase(
                $this->get(UserRepositoryInterface::class),
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        $this->factories[LogoutUseCase::class] = function (): LogoutUseCase {
            return new LogoutUseCase(
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        // Activity Use Cases
        $this->factories[TrackPageViewUseCase::class] = function (): TrackPageViewUseCase {
            return new TrackPageViewUseCase(
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        $this->factories[TrackButtonClickUseCase::class] = function (): TrackButtonClickUseCase {
            return new TrackButtonClickUseCase(
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        $this->factories[GetStatisticsUseCase::class] = function (): GetStatisticsUseCase {
            return new GetStatisticsUseCase(
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        $this->factories[GetReportUseCase::class] = function (): GetReportUseCase {
            return new GetReportUseCase(
                $this->get(ActivityRepositoryInterface::class)
            );
        };
    }

    private function registerControllers(): void
    {
        // Web Controllers
        $this->factories[HomeController::class] = function (): HomeController {
            return new HomeController(
                $this->get(Environment::class),
                $this->get(SessionInterface::class)
            );
        };

        $this->factories[AuthController::class] = function (): AuthController {
            return new AuthController(
                $this->get(Environment::class),
                $this->get(LoginUseCase::class),
                $this->get(RegisterUseCase::class),
                $this->get(LogoutUseCase::class),
                $this->get(SessionInterface::class)
            );
        };

        $this->factories[PageController::class] = function (): PageController {
            return new PageController(
                $this->get(Environment::class),
                $this->get(TrackPageViewUseCase::class),
                $this->get(SessionInterface::class)
            );
        };

        $this->factories[StatisticsController::class] = function (): StatisticsController {
            return new StatisticsController(
                $this->get(Environment::class),
                $this->get(ActivityRepositoryInterface::class),
                $this->get(UserRepositoryInterface::class),
                $this->get(SessionInterface::class)
            );
        };

        $this->factories[ReportsController::class] = function (): ReportsController {
            return new ReportsController(
                $this->get(Environment::class),
                $this->get(SessionInterface::class),
                $this->get(ActivityRepositoryInterface::class)
            );
        };

        // API Controllers
        $this->factories[ActivityController::class] = function (): ActivityController {
            return new ActivityController(
                $this->get(TrackButtonClickUseCase::class),
                $this->get(SessionInterface::class)
            );
        };

        $this->factories[UserController::class] = function (): UserController {
            return new UserController(
                $this->get(UserRepositoryInterface::class),
                $this->get(SessionInterface::class)
            );
        };
    }

    public function get(string $id): object
    {
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }

        if (isset($this->factories[$id])) {
            $this->services[$id] = $this->factories[$id]();
            return $this->services[$id];
        }

        throw new \InvalidArgumentException("Service '{$id}' not found in container");
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]) || isset($this->factories[$id]);
    }
}
