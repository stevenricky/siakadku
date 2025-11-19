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

# Create startup script untuk auto migration & seeder
RUN echo '#!/bin/bash\n\
set -e\n\
echo "=== SIAKADKU STARTUP ==="\n\
\n\
echo "1. Waiting for database connection..."\n\
for i in {1..30}; do\n\
  if php artisan db:monitor > /dev/null 2>&1; then\n\
    echo "✅ Database connected!"\n\
    break\n\
  fi\n\
  if [ $i -eq 30 ]; then\n\
    echo "❌ Database connection failed after 30 attempts"\n\
    exit 1\n\
  fi\n\
  echo "⏳ Waiting for database... ($i/30)"\n\
  sleep 1\n\
done\n\
\n\
echo "2. Creating missing columns if needed..."\n\
# Check if deleted_at column exists, if not add it\n\
php artisan tinker --execute="\n\
try {\n\
    \\\\DB::select(\\\"SELECT deleted_at FROM users LIMIT 1\\\");\n\
    echo \\\"✅ deleted_at column exists\\\\n\\\";\n\
} catch (\\\\Exception \$e) {\n\
    echo \\\"❌ deleted_at column missing, adding...\\\\n\\\";\n\
    \\\\DB::statement(\\\"ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_at\\\");\n\
    echo \\\"✅ deleted_at column added\\\\n\\\";\n\
}\n\
" || echo "Column check completed"\n\
\n\
echo "3. Running migrations..."\n\
php artisan migrate --force\n\
\n\
echo "4. Seeding database..."\n\
php artisan db:seed --force || echo "Seeder mungkin sudah jalan, lanjut..."\n\
\n\
echo "5. Cache optimization..."\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
\n\
echo "6. Starting application server..."\n\
echo "✅ SIAKADKU READY! Login dengan:"\n\
echo "   👨‍💼 Admin: admin@example.com / password123"\n\
echo "   👨‍🏫 Guru: guru@example.com / password123"\n\
echo "   🎓 Siswa: siswa@example.com / password123"\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
