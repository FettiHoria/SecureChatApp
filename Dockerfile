# Folosim imaginea oficială de PHP 8.3 cu FPM
FROM php:8.3-fpm

# Instalăm dependințe de bază + PostgreSQL dev
RUN apt-get update && apt-get install -y \
    gnupg2 \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd \
    && docker-php-ext-install pdo_pgsql pgsql

# Instalăm Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurăm Xdebug direct în Dockerfile
RUN echo "zend_extension=xdebug" > /usr/local/etc/php/conf.d/99-xdebug.ini \
 && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/99-xdebug.ini \
 && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/99-xdebug.ini \
 && echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/99-xdebug.ini \
 && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/99-xdebug.ini \
 && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/99-xdebug.ini \
 && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/99-xdebug.ini

# Instalăm Composer global
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setăm directorul de lucru
WORKDIR /var/www

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

