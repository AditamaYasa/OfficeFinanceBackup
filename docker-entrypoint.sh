#!/bin/bash
set -e

echo "[DEBUG] Starting entrypoint script..."

if [ ! -f ".env" ]; then
    echo "[DEBUG] Creating .env from .env.example..."
    cp .env.example .env 
fi

echo "[DEBUG] Generating APP_KEY if needed..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Jalankan migrations
echo "[DEBUG] Running migrations..."
php artisan migrate --force || true

# Start server
echo "[DEBUG] Starting server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}