#!/usr/bin/env bash
set -e

# WORKDIR sur Render = racine du repo; on force les chemins absolus
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

# Forcer la base SQLite et garantir le fichier (chemins absolus)
mkdir -p "$ROOT/database"
touch "$ROOT/database/database.sqlite"
chmod 664 "$ROOT/database/database.sqlite"
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
if grep -q '^DB_DATABASE=' .env; then
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$ROOT/database/database.sqlite|" .env
else
    echo "DB_DATABASE=$ROOT/database/database.sqlite" >> .env
fi
echo "==> SQLite DB: $ROOT/database/database.sqlite"

echo "==> Migrations + seeds"
php artisan migrate --force --seed

echo "==> Storage link"
php artisan storage:link || true

echo "==> Production safe (no config/route cache to avoid stale env)"
php artisan view:cache || true

echo "==> Starting server on port ${PORT:-8080}"
exec php -S 0.0.0.0:${PORT:-8080} -t public
