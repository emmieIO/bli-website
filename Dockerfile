# -------- Stage 1: Build Assets & Dependencies --------
FROM node:18 as node-builder

WORKDIR /app

# Copy only what's needed to install JS dependencies & build
COPY package*.json vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm install && npm run build


# -------- Stage 2: Composer Dependencies --------
FROM composer:2 as composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader


# -------- Stage 3: Final App Image --------
FROM php:8.2-cli-bullseye

# Install required PHP extensions and system deps
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Copy files from composer & node builders
COPY --from=composer-builder /app /var/www
COPY --from=node-builder /app/public/build /var/www/public/build

# Copy the rest of your Laravel app
COPY . .

# Cache Laravel config & views
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port Render expects
EXPOSE 8080

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
