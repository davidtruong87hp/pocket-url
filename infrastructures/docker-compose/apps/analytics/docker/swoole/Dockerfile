FROM php:8.4-cli AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  libpq-dev \
  libbrotli-dev \
  libcurl4-openssl-dev \
  libssl-dev \
  zip \
  unzip \
  supervisor \
  bash \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions @composer-2 

# Install PHP extensions
RUN docker-php-ext-install \
  pdo_pgsql \
  pgsql \
  mbstring \
  exif \
  pcntl \
  bcmath \
  gd \
  zip \
  sockets

# Install Swoole
RUN pecl install swoole \
  && docker-php-ext-enable swoole

# Install Redis extension
RUN pecl install redis \
  && docker-php-ext-enable redis

# Set working directory
WORKDIR /app

FROM base AS development

# Install PCOV
RUN pecl install pcov \
  && docker-php-ext-enable pcov \
  && pecl clear-cache

# Copy PCOV configuration
COPY infrastructures/docker-compose/apps/analytics/docker/swoole/pcov.ini /usr/local/etc/php/conf.d/pcov.ini

COPY apps/analytics/composer.json apps/analytics/composer.lock* ./
RUN composer install --no-interaction --no-scripts

COPY apps/analytics ./

# Create necessary directories with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

# Run post-install scripts
RUN composer dump-autoload --optimize

# Copy supervisor configuration
COPY infrastructures/docker-compose/apps/analytics/docker/swoole/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8001

# Start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
