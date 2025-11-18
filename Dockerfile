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

# Set working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files & install PHP dependencies
COPY composer.json composer.lock* ./
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Copy project source
COPY . .

# Install Node dependencies & build Vite
RUN npm install
RUN npm run build

CMD ["php-fpm"]
