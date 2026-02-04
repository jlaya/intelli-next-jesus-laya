FROM php:7.3-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias para Laravel y Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensiones de PHP (pdo_pgsql es vital para Postgres)
RUN docker-php-ext-install pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www