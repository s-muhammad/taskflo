# ---- Stage 1: build frontend assets (Vite/Tailwind/Alpine) ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Stage 2: install PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ---- Stage 3: final runtime image (php-fpm + nginx + supervisor bundled) ----
FROM serversideup/php:8.2-fpm-nginx AS production
USER root

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

# Vite dev-server marker must never ship to prod (bit us before on dploy too)
RUN rm -f public/hot

RUN chown -R www-data:www-data storage bootstrap/cache

# --- Laravel Automations (runs on every container start) ---
# This replaces everything dploy's before_commands used to do by hand:
# migrations, config/route/view caching, storage:link — all built into the
# base image and run safely (waits for DB, checks connection first).
ENV AUTORUN_ENABLED=true
ENV AUTORUN_LARAVEL_STORAGE_LINK=true
ENV AUTORUN_LARAVEL_MIGRATION=true
ENV AUTORUN_LARAVEL_CONFIG_CACHE=true
ENV AUTORUN_LARAVEL_ROUTE_CACHE=true
ENV AUTORUN_LARAVEL_VIEW_CACHE=true
ENV PHP_OPCACHE_ENABLE=1

RUN install-php-extensions gd

USER www-data
EXPOSE 8080