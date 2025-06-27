#!/usr/bin/env bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install JS dependencies and build Vite
npm ci && npm run build
