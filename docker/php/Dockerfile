# syntax=docker/dockerfile:labs
ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer

FROM php:${PHP_VERSION}-fpm AS php
WORKDIR /app
RUN --mount=type=cache,target=/var/cache/apt \
    apt-get update \
 && apt-get install -y --no-install-recommends \
      unzip

FROM php AS builder
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    composer install

FROM builder AS runtime
COPY --from=builder /app/vendor /app/vendor
COPY . /app
CMD ["php-fpm", "--pid", "/var/run/php-fpm.pid"]
