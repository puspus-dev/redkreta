# Dockerfile
FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Cache routes & config
RUN php artisan config:cache
RUN php artisan route:cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
