# ========= Backend: Laravel 12 (PHP-FPM + Nginx) =========
FROM php:8.3-fpm-bookworm AS base

# System deps (nginx + supervisor + pgsql)
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx supervisor git unzip libpq-dev libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-install pdo_pgsql intl zip \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy source
COPY . .

# Install PHP deps (prod) - sesuai composer.json Anda (Laravel 12, Sanctum, dll) :contentReference[oaicite:6]{index=6}
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

# Laravel folder permissions (storage & cache)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwx storage bootstrap/cache

# Nginx config inline (serve /public, pass php to php-fpm)
RUN rm -f /etc/nginx/sites-enabled/default \
 && printf '%s\n' \
'server {' \
'  listen 80;' \
'  server_name _;' \
'  root /var/www/html/public;' \
'  index index.php index.html;' \
'' \
'  client_max_body_size 20m;' \
'' \
'  location / {' \
'    try_files $uri $uri/ /index.php?$query_string;' \
'  }' \
'' \
'  location ~ \.php$ {' \
'    include fastcgi_params;' \
'    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' \
'    fastcgi_pass 127.0.0.1:9000;' \
'  }' \
'}' \
> /etc/nginx/conf.d/app.conf

# Supervisor config inline: run php-fpm + nginx
RUN printf '%s\n' \
'[supervisord]' \
'nodaemon=true' \
'' \
'[program:php-fpm]' \
'command=php-fpm -F' \
'autostart=true' \
'autorestart=true' \
'priority=10' \
'' \
'[program:nginx]' \
'command=nginx -g "daemon off;"' \
'autostart=true' \
'autorestart=true' \
'priority=20' \
> /etc/supervisor/conf.d/supervisord.conf

# Startup script inline: cache config/routes, storage:link, migrate (optional)
RUN printf '%s\n' \
'#!/usr/bin/env bash' \
'set -e' \
'' \
'php artisan config:cache || true' \
'php artisan route:cache || true' \
'php artisan view:cache || true' \
'' \
'# storage link public (FILESYSTEM_DISK=public di env Anda) :contentReference[oaicite:7]{index=7}' \
'php artisan storage:link || true' \
'' \
'# Jalankan migrasi saat boot (kalau Anda ingin). Jika tidak, comment baris ini.' \
'php artisan migrate --force || true' \
'' \
'exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' \
> /usr/local/bin/start.sh \
&& chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]
