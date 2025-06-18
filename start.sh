#!/bin/bash

# Wait for DB to be ready (optional but smart)
echo "Waiting for database..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  sleep 2
done

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Start built-in PHP server
php -S 0.0.0.0:80 -t public
