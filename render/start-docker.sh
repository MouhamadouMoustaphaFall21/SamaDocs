#!/usr/bin/env bash
set -e

echo "==> Preparing filesystem"
ROOT="/var/www/html"
mkdir -p "$ROOT/database"
touch "$ROOT/database/database.sqlite"
chown -R www-data:www-data "$ROOT/database"
chmod -R 775 "$ROOT/database"

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

# Forcer SQLite avec chemin absolu de maniere idempotente
if grep -q '^DB_CONNECTION=' .env; then
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
else
    echo "DB_CONNECTION=sqlite" >> .env
fi
if grep -q '^DB_DATABASE=' .env; then
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$ROOT/database/database.sqlite|" .env
else
    echo "DB_DATABASE=$ROOT/database/database.sqlite" >> .env
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
