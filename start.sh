#!/bin/bash

# Install dependencies (safe to run repeatedly)
composer install --no-interaction --optimize-autoloader

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction || true

# Start PHP server (or php-fpm if you're using a web server)
php -S 0.0.0.0:80 -t public
