FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
  supervisor \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
  pdo_mysql \
  pcntl \
  sockets \
  opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first (for better caching)
COPY apps/shortener/composer.json apps/shortener/composer.lock* ./

# Install application dependencies
RUN composer install \
  --no-dev \
  --optimize-autoloader \
  --no-interaction \
  --no-scripts \
  --prefer-dist

# Copy application files
COPY apps/shortener ./

# Run post-install scripts
RUN composer dump-autoload --optimize

# Copy supervisor configuration
COPY infrastructures/docker-compose/apps/shortener/docker/worker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create log directory
RUN mkdir -p /var/log/supervisor

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

# Start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]