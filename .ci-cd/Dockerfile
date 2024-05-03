FROM composer:2.5.5 as composer
WORKDIR /app

ARG DEV_BUILD

# ------------------------ Source code & seeders copy (used for classmap autoloader generation)
COPY ./app app
# COPY ./packages packages
COPY ./tests tests

# ------------------------ Dependencies declaration files copy
COPY ./composer.json composer.json
COPY ./composer.lock composer.lock

RUN if [ ! -z "${DEV_BUILD}" ]; then \
  composer install \
    --optimize-autoloader \
    --ignore-platform-reqs \
    --no-interaction \
    --prefer-dist \
    --classmap-authoritative \
    --no-plugins \
    --no-scripts; \
else \
    composer install \
    --optimize-autoloader \
    --ignore-platform-reqs \
    --no-interaction \
    --prefer-dist \
    --classmap-authoritative \
    --no-plugins \
    --no-scripts \
    --no-dev; \
fi

FROM php:8.2-fpm

WORKDIR /var/www/api

ARG APP_USER=www-data
ARG DEV_BUILD


# PHP_OPCACHE_VALIDATE_TIMESTAMPS: When disabled, you must reset the OPcache manually or restart the
# webserver for changes to the filesystem to take effect.
# For Development / testing, keep 1
# For performance / production, keep 0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

ENV PHP_MEMORY_LIMIT="256M"

EXPOSE 8080

RUN apt update -y && \
    apt-get install -y  \
      nginx \
      libzip-dev \
      libicu-dev \
      libonig-dev \
      libpng-dev  \
      libjpeg-dev  \
      libwebp-dev  \
      libfreetype6-dev \
      ghostscript

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get install -y --no-install-recommends --no-install-suggests supervisor cron procps lsof
RUN pecl install -o -f redis &&  rm -rf /tmp/pear &&  docker-php-ext-enable redis
RUN apt-get install -y \
    libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
	&& docker-php-ext-enable imagick

RUN apt-get install -y libxslt-dev && \
    docker-php-ext-install xsl && \
    apt-get clean
RUN apt-get install -y libicu-dev && \
    docker-php-ext-install intl && \
    apt-get clean

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install pdo_mysql gd zip bcmath exif pcntl gd

COPY . /var/www/api

#RUN chown -R ${APP_USER}:${APP_USER} /run /var/lib/nginx /var/log/nginx

COPY .ci-cd/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .ci-cd/nginx/.htpasswd /etc/nginx/conf.d/.htpasswd
COPY .ci-cd/php/zz-global.conf /usr/local/etc/php-fpm.d/zz-global.conf
COPY .ci-cd/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY .ci-cd/cron/scheduler /etc/cron.d/scheduler
COPY .ci-cd/php/docker-fpm.ini /usr/local/etc/php/conf.d/docker-fpm.ini

#COPY --chown=${APP_USER}:${APP_USER} .ci-cd/ ./.ci-cd

COPY --from=composer /app/vendor /var/www/api/vendor/

RUN chmod 644 /etc/cron.d/scheduler && crontab /etc/cron.d/scheduler

RUN chown -R ${APP_USER}:${APP_USER} /var/www/api/storage /var/www/api/bootstrap && \
    chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap && \
    rm -rfv .env*
#USER ${APP_USER}

RUN /usr/local/bin/composer dump-autoload
RUN chmod 775 ./.ci-cd/entrypoint.sh

#HEALTHCHECK --interval=10s --timeout=5m --start-period=30s CMD curl -f http://localhost/healthcheck || exit 1

CMD ["./.ci-cd/entrypoint.sh"]
