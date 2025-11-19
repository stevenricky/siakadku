FROM php:8.2-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip mbstring xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files FIRST
COPY composer.json composer.lock* ./

# Install dependencies
RUN composer install --no-dev --no-scripts --no-interaction --no-progress

# Copy app code
COPY . .

# CREATE CACHE FOLDERS AND SET PERMISSIONS
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache
RUN chmod -R 775 bootstrap/cache storage
RUN chown -R www-data:www-data bootstrap/cache storage

# Clear cache and disable maintenance mode during build
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true
RUN if [ -f "storage/framework/down" ]; then php artisan up; fi

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
