FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zip unzip git curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --no-scripts --no-interaction

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
