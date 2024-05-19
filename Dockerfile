FROM composer:lts as deps
WORKDIR /app
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:8.2-apache as final
RUN apt-get update && apt-get install -y cron
RUN docker-php-ext-install pdo pdo_mysql
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . /var/www/html
COPY --from=deps app/vendor/ /var/www/html/vendor
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY send-email-cron /etc/cron.d/send-email-cron
RUN chmod 0644 /etc/cron.d/send-email-cron
RUN crontab /etc/cron.d/send-email-cron
RUN touch /var/log/cron.log
CMD printenv > /etc/environment && cron && /usr/sbin/apache2ctl -D FOREGROUND
