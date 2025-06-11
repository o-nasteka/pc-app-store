#!/bin/bash
set -e

# Install PHP dependencies
composer install --no-interaction --prefer-dist

# Install JS dependencies and build frontend
npm install --legacy-peer-deps
npm run build

# Run migrations (if needed)
php bin/console migrations:migrate --no-interaction

# (Optional) Seed test data
# php bin/console analytics:seed

# Start PHP-FPM
exec php-fpm 