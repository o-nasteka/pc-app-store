<?php

declare(strict_types=1);

use App\Config\Bootstrap;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load environment variables
$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(__DIR__) . '/.env');

// Enable error handler in debug mode
if ($_ENV['APP_DEBUG'] === 'true') {
    Debug::enable();
}

// Create and run application
try {
    $app = new Bootstrap();
    $response = $app->handleRequest();
    $response->send();
} catch (\Exception $e) {
    // Basic error handling
    http_response_code(500);

    if ($_ENV['APP_DEBUG'] === 'true') {
        echo '<pre>';
        echo 'Error: ' . $e->getMessage() . "\n";
        echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
        echo 'Trace: ' . "\n" . $e->getTraceAsString();
        echo '</pre>';
    } else {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>Something went wrong. Please try again later.</p>';
    }

    // Log error
    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
}
