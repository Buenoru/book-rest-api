FROM php:7.4-fpm-alpine

RUN apk update \
    && apk --no-cache add \
        postgresql-dev

RUN docker-php-ext-install pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
