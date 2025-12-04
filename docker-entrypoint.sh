#!/bin/bash
set -e

# Generate key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate
fi

# Jalankan migrations
echo "Running migrations..."
php artisan migrate --force

# Start server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}