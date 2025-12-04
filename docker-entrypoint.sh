#!/bin/bash
set -e

echo "[DEBUG] Starting entrypoint script..."

if [ ! -f ".env" ]; then
    echo "[DEBUG] Creating .env from .env.example..."
    cp .env.example .env || echo "[WARNING] .env.example not found"
fi

# Generate key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate
fi

# Jalankan migrations
echo "[DEBUG] Running migrations..."
php artisan migrate --force

# Start server
echo "[DEBUG] Starting server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}