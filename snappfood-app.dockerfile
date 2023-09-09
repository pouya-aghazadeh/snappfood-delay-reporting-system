FROM php:fpm-bookworm

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql