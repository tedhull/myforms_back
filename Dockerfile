FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    nginx

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    opcache \
    ctype \
    iconv

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/project

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configure www-data user to match host user ID
RUN usermod -u 1000 www-data

# Set proper permissions
RUN chown -R www-data:www-data /var/www

# Set recommended PHP.ini settings
RUN echo "memory_limit=256M" > $PHP_INI_DIR/conf.d/memory-limit.ini \
    && echo "max_execution_time=600" >> $PHP_INI_DIR/conf.d/memory-limit.ini \
    && echo "upload_max_filesize=200M" >> $PHP_INI_DIR/conf.d/memory-limit.ini \
    && echo "post_max_size=200M" >> $PHP_INI_DIR/conf.d/memory-limit.ini

# Configure Nginx
COPY nginx/default.conf /etc/nginx/conf.d/
RUN echo "upstream php-upstream { server 127.0.0.1:9000; }" > /etc/nginx/conf.d/upstream.conf

# Create startup script
RUN echo '#!/bin/bash\n\
service nginx start\n\
php-fpm' > /usr/local/bin/start.sh && \
chmod +x /usr/local/bin/start.sh

# Expose port 80 for Nginx
EXPOSE 80

# Start both Nginx and PHP-FPM
CMD ["/usr/local/bin/start.sh"]
