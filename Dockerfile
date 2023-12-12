# Use the official PHP image
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql && apt-get clean

#RUN docker-php-ext-install php_zip php_xml php_gd2 php_gd2 php_simplexml php_xmlreader php_zlib
RUN docker-php-ext-install zip


# Set the working directory
WORKDIR /var/www/app

# Copy composer files
COPY composer.json composer.lock ./

# Copy the rest of the application
COPY . .

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

USER root

RUN chmod 777 -R /var/www/app

# Expose port 9000 and start php-fpm
EXPOSE 9000
CMD ["php-fpm"]
