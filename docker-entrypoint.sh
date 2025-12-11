#!/bin/sh
set -e

# If .env does not exist, create it from .env.example
if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$(grep '^APP_KEY=' .env | cut -d= -f2-)" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

chown -R www-data:www-data /var/www/html/storage 2>/dev/null || true
chown -R www-data:www-data /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

php artisan migrate --force --no-interaction || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm -D

exec "$@"
