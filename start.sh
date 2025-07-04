#!/bin/bash

# Set default port if not provided by Render
PORT=${PORT:-80}

# Print environment info (useful for debugging)
echo "Starting application in ${APP_ENV} mode on port ${PORT}"
# Clear cache for production
if [ "$APP_ENV" = "prod" ]; then
  echo "Clearing and warming up cache..."
  php bin/console cache:clear --no-warmup
  php bin/console cache:warmup
fi

# Start the server
echo "Starting PHP server on port ${PORT}..."
php -S 0.0.0.0:${PORT} -t public
