# Syntaxe stable
FROM php:8.1-fpm AS builder

# Dépendances système
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    nginx curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip gd mbstring exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code applicatif
COPY . .

# Désactiver les démons système gérés par le conteneur
RUN rm -f /etc/nginx/sites-enabled/default

# Installer les dépendances (sans dev)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Droits et dossiers
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copie de la config nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini

EXPOSE 8080

WORKDIR /var/www/html

CMD ["bash", "/var/www/html/render/start-docker.sh"]
