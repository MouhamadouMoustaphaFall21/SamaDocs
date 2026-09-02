#!/usr/bin/env bash
set -e

echo "==> Waiting for filesystem"
ROOT="/var/www/html"
mkdir -p "$ROOT/database"
touch "$ROOT/database/database.sqlite"
chown -R www-data:www-data "$ROOT/database"
chmod -R 775 "$ROOT/database"

echo "==> .env preparation"
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Générer une clé si absente
if ! grep -q '^APP_KEY=[^[:space:]]' .env; then
    php artisan key:generate --force
fi

# Forcer SQLite avec chemin absolu
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
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

# Lancement de PHP-FPM en arrière-plan, nginx au premier plan
php-fpm -D
echo "==> Starting nginx on :8080"
exec nginx -g "daemon off;"
