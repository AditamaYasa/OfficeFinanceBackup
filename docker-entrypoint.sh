#!/bin/sh
set -e

# Create .env if not exists
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY jika belum ada (penting!)
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Set permissions
chown -R www-data:www-data /var/www/html/storage 2>/dev/null || true
chown -R www-data:www-data /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm -D

exec "$@"