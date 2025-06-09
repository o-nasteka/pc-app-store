<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/src/Domain/Entity'],
    isDevMode: true,
);

$connection = [
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => 'pdo_mysql',
    'charset' => 'utf8mb4',
];

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    EntityManager::create($connection, $config)
);
