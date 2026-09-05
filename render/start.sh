#!/usr/bin/env bash
set -e

# WORKDIR sur Render = racine du repo
ROOT="$(pwd)"
export APP_ENV=${APP_ENV:-production}

echo "==> Composer install (no-dev)"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "==> .env configuration"
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Générer une clé d'application si absente
if ! grep -q '^APP_KEY=[^[:space:]]' .env; then
    php artisan key:generate --force
fi

# Forcer PostgreSQL (DATABASE_URL fournie par Render via le service managé)
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env || true
grep -q '^DB_CONNECTION=' .env || echo "DB_CONNECTION=pgsql" >> .env
if [ -n "$DATABASE_URL" ]; then
    sed -i "s|^DATABASE_URL=.*|DATABASE_URL=$DATABASE_URL|" .env || true
    grep -q '^DATABASE_URL=' .env || echo "DATABASE_URL=$DATABASE_URL" >> .env
fi

echo "==> Migrations + seeds"
php artisan migrate --force --seed

echo "==> Storage link"
php artisan storage:link || true

echo "==> Production safe (no config/route cache to avoid stale env)"
php artisan view:cache || true

echo "==> Starting server on port ${PORT:-8080}"
exec php -S 0.0.0.0:${PORT:-8080} -t public