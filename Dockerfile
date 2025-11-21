FROM php:8.2-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo zip mbstring xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --no-dev --no-interaction --no-progress --no-scripts

# HAPUS SEMUA MIGRATION FILES
RUN rm -rf database/migrations/

COPY . .

RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# SIMPLE STARTUP - NO MYSQL CHECK
RUN echo '#!/bin/sh\n\
echo "🚀 Starting Siakadku..."\n\
\n\
echo "📦 Caching configurations..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
\n\
echo "✅ Application ready!"\n\
exec apache2-foreground\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
