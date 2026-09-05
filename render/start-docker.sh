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
export DATABASE_URL
if [ -z "$DATABASE_URL" ]; then
    echo "!! DATABASE_URL manquante : la base PostgreSQL n'a pas été branchée sur ce service."
    echo "!! Créez une base (Render > New + > PostgreSQL) et ajoutez DATABASE_URL aux env vars."
    exit 1
fi
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env || true
grep -q '^DB_CONNECTION=' .env || echo "DB_CONNECTION=pgsql" >> .env
if grep -q '^DATABASE_URL=' .env; then
    sed -i "s|^DATABASE_URL=.*|DATABASE_URL=$DATABASE_URL|" .env
else
    echo "DATABASE_URL=$DATABASE_URL" >> .env
fi

echo "==> Migrations + seeds"
php artisan migrate --database=pgsql --force --seed

echo "==> Storage link"
php artisan storage:link || true

echo "==> Cache (safe)"
php artisan view:cache || true

echo "==> Starting PHP-FPM + nginx on :8080"
php-fpm -D || true
exec nginx -g "daemon off;"