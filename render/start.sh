#!/usr/bin/env bash
set -e

echo "==> Composer install (no-dev)"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "==> .env configuration"
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Générer une clé d'application si absente
if ! grep -q '^APP_KEY=base64:' .env && ! grep -q '^APP_KEY=[^=]' .env; then
    php artisan key:generate --force
fi

# S'assurer que la DB SQLite existe
if [ ! -f database/database.sqlite ]; then
    mkdir -p database
    touch database/database.sqlite
    echo "==> SQLite file created (ephemeral)"
fi

echo "==> Migrations + seeds"
php artisan migrate --force --seed || true

echo "==> Storage link"
php artisan storage:link || true

echo "==> Cache config"
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Starting server on port ${PORT:-8080}"
exec php -S 0.0.0.0:${PORT:-8080} -t public
