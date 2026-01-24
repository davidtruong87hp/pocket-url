FROM php:8.4-cli AS base

# Install dependencies
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpq-dev \
  libssl-dev \
  libcurl4-openssl-dev \
  libbrotli-dev \
  zlib1g-dev \
  zip \
  unzip \
  supervisor \
  cron \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions @composer-2

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql sockets pcntl opcache

# Install Swoole
RUN pecl install swoole \
  && docker-php-ext-enable swoole

# Install Redis extension
RUN pecl install redis \
  && docker-php-ext-enable redis

# Set working directory
WORKDIR /app

FROM base AS development

# Install dependencies
COPY apps/analytics/composer.json apps/analytics/composer.lock* ./
RUN composer install --no-interaction --no-scripts

COPY apps/analytics ./

# Create necessary directories with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

# Run post-install scripts
RUN composer dump-autoload --optimize

# Copy supervisor configuration
COPY infrastructures/docker-compose/apps/analytics/docker/worker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create log directories
RUN mkdir -p /var/log/supervisor

# Start supervisor
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
