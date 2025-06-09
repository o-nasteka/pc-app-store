<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

$paths = [__DIR__ . '/../src/Domain']; // Adjust path to your entities
$isDevMode = true;

// Get DB config from environment
$dbParams = [
    'dbname'   => getenv('DB_NAME'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'driver'   => 'pdo_mysql',
];

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);

return new EntityManager($connection, $config);
