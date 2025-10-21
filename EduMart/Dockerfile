# ---------------------------------------
# Stage 1: Build dependencies with Composer
# ---------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# ---------------------------------------
# Stage 2: Application setup (PHP + Apache)
# ---------------------------------------
FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy existing application code
COPY . .

# Copy dependencies from vendor stage
COPY --from=vendor /app/vendor ./vendor

# Copy environment example
RUN cp .env.example .env

# Generate Laravel app key
RUN php artisan key:generate

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
