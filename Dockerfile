FROM php:8.2-apache

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

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --no-dev --no-interaction --no-progress --no-scripts

COPY . .

RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Apache configuration
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN echo '#!/bin/sh\n\
echo "🚀 Starting Siakadku..."\n\
\n\
echo "⏳ Waiting for MySQL..."\n\
until mysqladmin ping -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent; do\n\
  sleep 2\n\
done\n\
echo "✅ DB ready!"\n\
\n\
# Skip migrations if they fail\n\
php artisan migrate --force --no-interaction || true\n\
\n\
php artisan config:cache\n\
php artisan route:cache\n\
\n\
echo "✅ Application ready!"\n\
exec apache2-foreground\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
