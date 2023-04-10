# Use the official PHP 8.0 image as the base image
FROM php:8.1

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


# Copy the the application to the container
COPY . .

RUN composer install --no-scripts --no-autoloader

# Generate the Composer autoload file
RUN composer dump-autoload --optimize
