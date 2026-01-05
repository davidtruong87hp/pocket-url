FROM dunglas/frankenphp

RUN install-php-extensions \
    pdo_mysql \
    opcache \
    xdebug

# Copy Xdebug configuration
COPY infrastructures/docker-compose/apps/shortener/docker/frankenphp/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ENV SERVER_NAME=:80
