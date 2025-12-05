#!/bin/sh
set -e

# Generate APP_KEY if not exists
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

exec "$@"
