# Dockerfile
FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql

COPY ./vhost/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN pecl install xdebug-3.2.1 \
    && docker-php-ext-enable xdebug