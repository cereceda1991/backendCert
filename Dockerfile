FROM php:8.1-fpm

# Install required extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql mongodb \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy source code
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev

# Expose port 8000
EXPOSE 8000

# Start PHP-FPM server
CMD ["php-fpm"]
