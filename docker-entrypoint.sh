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

echo "[DEBUG] Running migrations..."
php artisan migrate --force || true

echo "[DEBUG] Starting server..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
