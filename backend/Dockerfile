FROM php:7.4-fpm-alpine as php-fpm

ARG WITH_XDEBUG=false

# Install required modules.
RUN apk --no-cache add \
        postgresql-dev \
# Install php extensions.
    && docker-php-ext-install \
        pdo_pgsql \
        opcache \
    && if [ "${WITH_XDEBUG}" = "true" ] ; then \
        apk add --update --no-cache $PHPIZE_DEPS; \
        pecl install xdebug; \
        docker-php-ext-enable xdebug; \
        echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.remote_handler = dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.client_port = 9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi \
    && rm -rf /tmp/* /var/cache/apk/* \
    && rm -rf /var/www

COPY .docker/fpm/custom.ini /usr/local/etc/php/conf.d/custom.ini
COPY .docker/fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY .docker/fpm/bootstrap.dev.sh /

RUN chown www-data:www-data /var/www \
    && chmod +x /bootstrap.dev.sh

USER www-data
WORKDIR /var/www

# Install dependencies.
FROM composer:1.9 as vendor

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-autoloader \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-progress \
    --no-scripts \
    --prefer-dist

COPY --chown=www-data:www-data . ./
RUN composer dump-autoload --no-scripts --no-dev --optimize

FROM php-fpm

# Build application.
COPY --chown=www-data:www-data . /var/www/
COPY --from=vendor --chown=www-data:www-data /app/vendor/ /var/www/vendor/

ARG TAG_VERSION
ARG COMMIT_SHA

ENV APP_ENV=prod
ENV APP_NAME=da4a-shop
ENV PHP_OPCACHE=1
ENV TAG_VERSION=$TAG_VERSION
ENV COMMIT_SHA=$COMMIT_SHA
