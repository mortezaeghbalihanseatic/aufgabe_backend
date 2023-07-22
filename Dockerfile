FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction

# RUN composer require "darkaonline/l5-swagger:~8.0"

# Debugger

# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug


RUN chown -R www-data:www-data /var/www/html


CMD php artisan serve --host=0.0.0.0 --port=8000

# CMD php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
