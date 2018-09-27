ARG PHP_VERSION_NAME=7.1
FROM php:${PHP_VERSION_NAME}-fpm-alpine

RUN apk add --no-cache --update --virtual build-dependencies build-base autoconf git openssl-dev

RUN pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN git clone https://github.com/tideways/php-profiler-extension.git \
    && cd php-profiler-extension \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable tideways_xhprof

RUN apk del build-dependencies

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/app