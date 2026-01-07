FROM dunglas/frankenphp:php8.4 AS base

RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions @composer-2 

# Install PHP extensions
RUN install-php-extensions \
  pdo_mysql \
  opcache \
  redis \
  pcntl \
  zip \
  protobuf

# Set working directory
WORKDIR /app

# Development stage
FROM base AS development

COPY apps/redirector/composer.json apps/redirector/composer.lock* ./
RUN composer install --no-interaction --no-scripts

COPY apps/redirector ./

# Run post-install scripts
RUN composer dump-autoload --optimize

# Create necessary directories with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

# Switch to non-root user for security
USER www-data

EXPOSE 8000

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]

# Production stage
FROM base AS production

COPY apps/redirector/composer.json apps/redirector/composer.lock* ./
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Copy application files
COPY apps/redirector ./

# Optimize Laravel
RUN php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache

# Create necessary directories with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

# Switch to non-root user for security
USER www-data

# Expose port
EXPOSE 8000

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
