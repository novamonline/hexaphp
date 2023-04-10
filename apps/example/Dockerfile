ARG BASE_IMAGE="hexapp:latest"
FROM $BASE_IMAGE as stage

# Set the working directory to /app
WORKDIR /app

# Install application dependencies
RUN composer install --no-scripts --no-autoloader

# Generate the Composer autoload file
RUN composer dump-autoload --optimize

FROM php:8.0-fpm-alpine

COPY --from=stage app/apps/example /app
COPY --from=stage app/vendor /app/vendor

RUN composer dump-autoload

# Expose port 8000 for the PHP built-in web server
EXPOSE 8000

# Start the PHP built-in web server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]