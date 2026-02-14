FROM php:8.2-apache

# Cài PostgreSQL driver cho PDO
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy source vào Apache
COPY . /var/www/html/

# Set quyền
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80