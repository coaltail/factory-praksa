# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . .

# Install any dependencies using Composer
RUN apt-get update && \
    apt-get install -y zip unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-scripts --no-autoloader && \
    composer dump-autoload --optimize
RUN docker-php-ext-install pdo_mysql
# Make port 80 available to the world outside this container
EXPOSE 80

# Define environment variable
ENV NAME DockerizedPHPApp
RUN a2enmod rewrite
# Run app when the container launches
CMD ["apache2-foreground"]