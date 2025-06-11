#!/bin/bash
set -e

# Create .env from .env.example if not exists
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Wait for MySQL to be ready
until mysql -h mysql -uapp_user -papp_password -e "SELECT 1" activity_tracker; do
  echo "Waiting for MySQL to be ready..."
  sleep 2
done

# Install PHP dependencies
composer install --no-interaction --prefer-dist

# Install JS dependencies and build frontend
npm install --legacy-peer-deps
npm run build

# Run migrations (if needed)
php bin/console migrations:migrate --no-interaction

# Create admin user (idempotent)
php bin/console app:create-admin

# Seed analytics data (idempotent)
php bin/console analytics:seed || true

# Start PHP-FPM
exec php-fpm 