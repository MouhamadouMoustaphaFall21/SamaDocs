FROM php:8.2-fpm

# Installation des dépendances système (y compris libonig-dev pour mbstring)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    pkg-config \
    nginx \
    curl \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip gd mbstring exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers de l'application
COPY . .

# Installation des dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configuration des permissions pour les dossiers de stockage/cache (si Laravel/Symfony)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Exposition du port Nginx
EXPOSE 80

# Démarrage de Nginx et PHP-FPM
CMD service nginx start && php-fpm