FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    libpq-dev \
    pkg-config \
    nginx \
    curl \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite pgsql pdo_pgsql zip gd mbstring exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie des fichiers du projet
COPY . .

# Remplacement du fichier principal de configuration Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configuration PHP (limites d'upload, mémoire, temps d'exécution)
COPY docker/php.ini /usr/local/etc/php/conf.d/samadocs.ini

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposition du port 8080 (défini dans votre nginx.conf)
EXPOSE 8080

# Démarrage (préparation .env, clé, PostgreSQL, migrations, puis PHP-FPM + Nginx)
CMD bash render/start-docker.sh