FROM php:8.2-fpm-alpine

# Dependensi sistem + ekstensi PHP (termasuk driver PostgreSQL)
RUN apk add --no-cache nginx libpq \
    && docker-php-ext-install pdo_pgsql

# Composer (binary phar, cross-runtime)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin seluruh source aplikasi
COPY . .

# Install dependensi production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Direktori yang harus writable oleh user www-data
RUN chown -R www-data:www-data storage bootstrap/cache \
    && mkdir -p /run/nginx

# Konfigurasi nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

EXPOSE 8080

CMD ["/bin/sh", "-c", "php artisan migrate --force --no-interaction; php artisan storage:link --force; php-fpm & nginx -g 'daemon off;'"]
