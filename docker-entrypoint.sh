#!/bin/bash
set -e

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

php artisan key:generate --force 2>/dev/null || true
php artisan config:clear || true
php artisan migrate --force 2>/dev/null || true

exec php artisan octane:start --host=0.0.0.0 --port=9000
