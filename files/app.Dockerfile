FROM composer:2.1.14 AS build
WORKDIR /app

COPY composer.json .
COPY composer.lock .
RUN composer install --no-dev --no-scripts --ignore-platform-reqs

COPY . .
RUN composer dumpautoload --optimize

FROM php:8.0.25-fpm-alpine3.16 as app

RUN set -ex \
	&& apk --no-cache add postgresql-libs postgresql-dev \
	&& docker-php-ext-install pgsql pdo_pgsql \
	&& apk del postgresql-dev

COPY --from=build /app /var/www/html
COPY /usr/local/etc/php /var/www/html/config

FROM nginx:alpine3.18

COPY --from=app /var/www/html/config /usr/local/etc/php
COPY --from=app /var/www/html /var/www/html
COPY --from=app /var/www/html/files/nginx.conf /etc/nginx/conf.d/default.conf

RUN mv /usr/local/etc/php/conf.d/php.ini-development /usr/local/etc/php/conf.d/php.ini
RUN sed -i 's|;extension=pdo_pgsql|extension=pdo_pgsql' /usr/local/etc/php/conf.d/php.ini
RUN sed -i 's|;extension=pgsql|extension=pgsql' /usr/local/etc/php/conf.d/php.ini
COPY ./files/8.0/fpm/pool.d/www.conf /usr/local/etc/php-fpm.d/www.conf

# EXPOSE 80
# CMD ["/app/bin/timetraq-dashboard"]