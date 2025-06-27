FROM php:8.2-cli-bullseye

# Install system deps
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip \
    libpng-dev libonig-dev libxml2-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get upgrade -y && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working dir
WORKDIR /var/www

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy and install dependencies
COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Laravel config cache (optional)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port for Render (must be 8080)
EXPOSE 8080

# Entry point
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
