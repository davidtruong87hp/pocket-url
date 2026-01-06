# Get the RoadRunner binary
FROM ghcr.io/roadrunner-server/roadrunner:2024 AS roadrunner

# =======================================================
# Development Stage 
# =======================================================
FROM php:8.5-alpine AS development

# Install the php-extension-installer script
RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions @composer-2 \
  opcache \
  pdo_mysql \
  pcntl \
  sockets \
  redis \
  zip \
  xdebug \
  pcov

# Copy the RoadRunner binary from the first stage
COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr
RUN chmod +x /usr/local/bin/rr

# Copy Xdebug configuration
COPY infrastructures/docker-compose/apps/shortener/docker/roadrunner/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy PCOV configuration
COPY infrastructures/docker-compose/apps/shortener/docker/roadrunner/pcov.ini /usr/local/etc/php/conf.d/pcov.ini

# Set your application's working directory
WORKDIR /app

# Copy composer files first for better layer caching
COPY apps/shortener/composer.json apps/shortener/composer.lock* ./

# Install dependencies
RUN composer install --no-interaction --no-scripts

# Copy the rest of the application
COPY apps/shortener .

# Run composer dump-autoload to ensure everything is loaded
RUN composer dump-autoload --optimize

# Clear config cache to avoid stale config
RUN php artisan config:clear || true

# Ensure storage directories have correct permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

# Switch to www-data user
USER www-data

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--host=0.0.0.0", "--port=8000"]

# =======================================================
# Production Stage
# =======================================================
FROM php:8.5-alpine as production

# Install system dependencies and PHP extensions (no xdebug)
RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions @composer-2 \
  opcache \
  pdo_mysql \
  pcntl \
  sockets \
  redis \
  zip

# Copy the RoadRunner binary
COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr
RUN chmod +x /usr/local/bin/rr

WORKDIR /app

# Copy composer files
COPY apps/shortener/composer.json apps/shortener/composer.lock* ./

# Install production dependencies only
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Copy application files
COPY apps/shortener .

# Run composer scripts and optimizations
RUN composer dump-autoload --optimize

# Laravel optimizations for production
RUN php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache

# Create necessary directories with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

# Switch to non-root user for security
USER www-data

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--host=0.0.0.0", "--port=8000"]