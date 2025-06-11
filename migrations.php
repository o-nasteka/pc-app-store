<?php

return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
    ],
    'migrations_paths' => [
        'App\\Infrastructure\\Migration' => './src/Infrastructure/Migration',
    ],
    'all_or_nothing' => true,
    'check_database_platform' => true,
];
