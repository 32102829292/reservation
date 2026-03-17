FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    nginx curl zip unzip git \
    libzip-dev libonig-dev libxml2-dev \
    libpq-dev libicu-dev libpng-dev \
    libjpeg-dev libfreetype6-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_pgsql pgsql \
        zip mbstring xml intl gd

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY docker/nginx.conf /etc/nginx/sites-available/default

RUN mkdir -p writable/cache writable/logs writable/session writable/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/writable

EXPOSE 80
CMD service nginx start && php-fpm