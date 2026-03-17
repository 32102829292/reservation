FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libicu-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions separately
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip mbstring xml intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# CI4 writable folder permissions
RUN mkdir -p writable/cache writable/logs writable/session writable/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/writable

# Expose port
EXPOSE 80

# Start nginx + php-fpm
CMD service nginx start && php-fpm