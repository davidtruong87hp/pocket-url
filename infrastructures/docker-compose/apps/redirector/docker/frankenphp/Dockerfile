FROM dunglas/frankenphp AS base

# Install PHP extensions
RUN install-php-extensions \
  pdo_mysql \
  opcache \
  redis \
  pcntl \
  zip

# Set working directory
WORKDIR /app

# Install Composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Development stage
FROM base AS development

COPY apps/redirector ./

# Install dependencies including Octane
RUN composer require laravel/octane && \
  composer install --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]

# Production stage
FROM base AS production

# Copy application files
COPY apps/redirector ./

# Install Octane first, then production dependencies
RUN composer require laravel/octane && \
  composer install --optimize-autoloader --no-dev

# Optimize Laravel
RUN php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Expose port
EXPOSE 8000

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
