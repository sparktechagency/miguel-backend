# Use the official PHP 8.2 image from Docker Hub
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies for Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer (Laravel's PHP package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader

# Expose the port that Laravel is running on (default is 8000)
EXPOSE 8000

# Run Laravel's built-in server (optional, for dev purposes)
CMD php artisan serve --host=0.0.0.0 --port=8000
