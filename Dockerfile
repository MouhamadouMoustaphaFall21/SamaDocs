# Syntaxe stable
# v3 - fix sqlite headers + invalidation cache
FROM php:8.1-fpm

# Dépendances système (une seule couche, invalidation via ENV build)
ENV SAMADOCS_BUILD=2026-09-02-3
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libsqlite3-dev pkg-config nginx curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip gd mbstring exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code applicatif
COPY . .

# Installer les dépendances (sans dev)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Droits et dossiers
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copie de la config nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini

# Désactiver le site nginx par defaut si present
RUN rm -f /etc/nginx/sites-enabled/default

EXPOSE 8080

WORKDIR /var/www/html

CMD ["bash", "/var/www/html/render/start-docker.sh"]
