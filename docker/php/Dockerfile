FROM php:7.2-fpm

ARG username
ARG useruid

ENV TERM=xterm \
    TZ=Europe/Moscow \
    PHP_IDE_CONFIG="serverName=$username" \
    USERNAME=$username

ENV PHP_EXTRA_CONFIGURE_ARGS --enable-fpm --with-fpm-user=$useruid --with-fpm-group=$useruid

### install dependences
RUN apt-get update && apt-get install -y git curl nano sudo

### Create user
RUN groupadd --gid $useruid $username \
    && useradd --uid $useruid --gid $username --shell /bin/bash --create-home $username
RUN usermod -a -G root $username \
    && chmod 777 -R /var/log \
    && echo "%$USERNAME ALL=(ALL:All) NOPASSWD:ALL" >> /etc/sudoers

### Install packages from Debian repository and install php-extensions
RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libssl-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libedit-dev \
        zlib1g-dev \
        libicu-dev \
        libxml2-dev \
        libpq-dev

### extensions install
RUN docker-php-ext-install -j$(nproc) iconv curl json opcache readline intl bcmath zip xml mbstring

### Pecl extensions install
RUN pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug \
    && mv /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.disabled

### Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

### User rights for config 
RUN chown $username:$username -R /usr/local/etc/php/conf.d/

### Switchable entrypoint
COPY entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chown $username:$username /usr/local/bin/docker-php-entrypoint \
    && chmod 777 /usr/local/bin/docker-php-entrypoint \
    && chmod +x /usr/local/bin/docker-php-entrypoint

### Debuggable php
COPY debuggable-php.sh /usr/local/bin/debuggable-php
RUN chown root:staff /usr/local/bin/debuggable-php \
    && chmod 777 /usr/local/bin/debuggable-php \
    && chmod +x /usr/local/bin/debuggable-php

RUN git clone https://github.com/tideways/php-profiler-extension.git \
    && cd php-profiler-extension \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable tideways_xhprof

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

USER $username