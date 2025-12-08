#!/bin/sh
set -e

# Pastikan storage writeable
chmod -R 775 storage bootstrap/cache || true

# Jika APP_KEY belum diset di Railway, generate 1x lalu simpan ke file
if [ ! -f /var/www/html/storage/app/app_key.txt ]; then
    php artisan key:generate --show > /var/www/html/storage/app/app_key.txt
fi

export APP_KEY=$(cat /var/www/html/storage/app/app_key.txt)

exec "$@"