#!/usr/bin/env bash
set -e

echo "==> Preparing filesystem"
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "==> .env preparation"
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        touch .env
    fi
fi

# Générer une clé si absente (respecte APP_KEY injectee par Render si fournie)
if [ -z "${APP_KEY}" ] && ! grep -q '^APP_KEY=[^[:space:]]' .env; then
    php artisan key:generate --force
fi

# Forcer PostgreSQL (DATABASE_URL est fournie par Render via le service managé)
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env || true
grep -q '^DB_CONNECTION=' .env || echo "DB_CONNECTION=pgsql" >> .env
if [ -n "$DATABASE_URL" ]; then
    sed -i "s|^DATABASE_URL=.*|DATABASE_URL=$DATABASE_URL|" .env || true
    grep -q '^DATABASE_URL=' .env || echo "DATABASE_URL=$DATABASE_URL" >> .env
    # Les variables DB_* individuelles peuvent rester, DATABASE_URL prime dans Laravel
fi

echo "==> Migrations + seeds"
php artisan migrate --force --seed

echo "==> Storage link"
php artisan storage:link || true

echo "==> Cache (safe)"
php artisan view:cache || true

echo "==> Starting PHP-FPM + nginx on :8080"
php-fpm -D || true
exec nginx -g "daemon off;"