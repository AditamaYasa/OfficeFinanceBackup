#!/bin/sh
set -e

# If .env does not exist, create it from .env.example
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY only if empty
if ! grep -q "APP_KEY=" .env || [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

exec "$@"
