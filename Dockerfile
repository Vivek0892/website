# Use PHP 8.0 with Apache
FROM php:8.0-apache

# Install system dependencies (if any)
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the web content into the container
COPY src/. /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80

# Start Apache server in the foreground
CMD ["apache2-foreground"]

