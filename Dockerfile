FROM php:8.2-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev \
    default-mysql-client zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip mbstring xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock* ./

# Install vendor
RUN composer install --no-dev --no-interaction --no-progress --no-scripts

# Copy full code
COPY . .

# Fix permissions
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Startup script
RUN echo '#!/bin/sh
echo "🚀 Starting Siakadku..."

# Wait for DB
echo "⏳ Waiting for MySQL..."
until mysqladmin ping -h "$MYSQLHOST" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" --silent; do
  sleep 2
done
echo "✅ DB ready!"

# Run migrations
php artisan migrate --force

# Run seed (optional)
php artisan db:seed --force || true

# Optimize
php artisan config:cache
php artisan route:cache

# Start server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
' > /start.sh \
    && chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
