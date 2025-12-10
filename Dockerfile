FROM node:18 AS build-assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .
RUN npm run build

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev unzip git zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

COPY --from=build-assets /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader
RUN composer dump-autoload

COPY default.conf /etc/nginx/conf.d/default.conf.template

# 2. PERBAIKAN: Copy script entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 3. PERBAIKAN: Copy supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 4. PERBAIKAN: Copy php-fpm.conf yang sebelumnya lupa dicopy
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

RUN apt-get install -y gettext-base

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 8080

# 5. PERBAIKAN: CMD membaca dari template yang benar
CMD ["sh", "-c", "envsubst '${PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf && supervisord -c /etc/supervisor/conf.d/supervisord.conf"]
