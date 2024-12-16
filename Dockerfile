FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y     git zip unzip libicu-dev libpq-dev curl     && docker-php-ext-install intl pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files to container
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80
