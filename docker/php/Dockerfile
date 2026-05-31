FROM php:8.3-fpm-bookworm

ARG WWWUSER=1000
ARG WWWGROUP=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        default-mysql-client \
        git \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libssl-dev \
        libzip-dev \
        pkg-config \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" bcmath gd intl opcache pcntl pdo_mysql zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /tmp/pear ~/.pearrc /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-nexusgear.ini

RUN groupmod -o -g "${WWWGROUP}" www-data \
    && usermod -o -u "${WWWUSER}" -g www-data www-data \
    && mkdir -p /var/www/html /tmp/composer \
    && chown -R www-data:www-data /var/www/html /tmp/composer

WORKDIR /var/www/html

ENV COMPOSER_HOME=/tmp/composer

USER www-data

CMD ["php-fpm"]
