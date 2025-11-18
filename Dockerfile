# Gunakan image PHP 8.2 FPM
FROM php:8.2-fpm

# Install dependencies sistem & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git curl \
    nodejs npm \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd pdo pdo_mysql \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer.json & composer.lock (jika ada)
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Copy seluruh source code project
COPY . .

# Install Node.js dependencies & build Vite
RUN npm install
RUN npm run build

# Expose port (opsional, jika mau akses langsung container)
EXPOSE 8000

# Jalankan PHP-FPM
CMD ["php-fpm"]
