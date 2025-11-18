FROM php:8.2-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip unzip git curl \
    nodejs npm \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd pdo pdo_mysql zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files & install PHP dependencies
COPY composer.json composer.lock* ./
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Copy seluruh source code project
COPY . .

# Laravel cache (production)
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Install Node.js dependencies & build Vite
RUN npm install \
 && npm run build

# Run PHP-FPM
CMD ["php-fpm"]
