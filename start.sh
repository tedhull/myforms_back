#!/bin/bash

# Set default port if not provided by Render
PORT=${PORT:-80}

# Print environment info (useful for debugging)
echo "Starting application in ${APP_ENV} mode on port ${PORT}"

# Wait for DB to be ready with timeout
echo "Waiting for database connection..."
MAX_TRIES=30
COUNT=0
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  COUNT=$((COUNT + 1))
  if [ $COUNT -ge $MAX_TRIES ]; then
    echo "Error: Could not connect to the database after ${MAX_TRIES} attempts."
    exit 1
  fi
  echo "Attempt ${COUNT}/${MAX_TRIES}: Database not ready yet. Waiting..."
  sleep 2
done
echo "Database connection established!"

# Run migrations (don't create migrations in production)
if [ "$APP_ENV" != "prod" ]; then
  echo "Creating migration if needed..."
  php bin/console doctrine:make:migration --no-interaction
fi

echo "Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# Clear cache for production
if [ "$APP_ENV" = "prod" ]; then
  echo "Clearing and warming up cache..."
  php bin/console cache:clear --no-warmup
  php bin/console cache:warmup
fi

# Start the server
echo "Starting PHP server on port ${PORT}..."
php -S 0.0.0.0:${PORT} -t public
