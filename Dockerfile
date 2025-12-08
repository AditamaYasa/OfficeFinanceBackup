# STAGE 1 – Build assets (Vite)
FROM node:18 AS build-assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js .
COPY postcss.config.js .

RUN npm run build

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip 

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

COPY --from=build-assets /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader

RUN composer dump-autoload


COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
