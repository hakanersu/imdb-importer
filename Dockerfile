FROM php:8-fpm-alpine as imdb_importer

RUN apk update && apk add zip git libzip-dev postgresql-client postgresql-dev

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_pgsql bcmath
