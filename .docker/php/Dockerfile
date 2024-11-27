FROM php:8.3-fpm-alpine

# Copy php config
COPY ./conf/php.ini /usr/local/etc/php/php.ini

# Packages
RUN apk add --update --no-cache curl git build-base zlib-dev oniguruma-dev autoconf icu-dev bash linux-headers

# Clean up package cache
RUN rm -rf /var/cache/apk/*

# Postgres
RUN apk add --no-cache libpq-dev \
  && docker-php-ext-install pdo_pgsql

# Intl
RUN docker-php-ext-configure intl \
  && docker-php-ext-install intl

# OPCache (if you need it by any means)
# RUN docker-php-ext-install opcache

# XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug;

# Xdebug config
COPY ./conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

# Set working directory
WORKDIR /var/www