FROM php:8.2-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy composer & install dependencies
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Copy project source
COPY . .

# Install Node & build Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs
RUN npm install
RUN npm run build

CMD ["php-fpm"]
