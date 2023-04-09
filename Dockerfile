# Use the official PHP 8.0 image as the base image
FROM php:8.0

# Set the working directory to /app
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install zip mysqli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the composer files to the container
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application to the container
COPY . .

# Generate the Composer autoload file
RUN composer dump-autoload --optimize
