FROM php:8.2-apache

# Cài PostgreSQL driver cho PDO
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Đổi Apache sang port 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/g' /etc/apache2/sites-available/000-default.conf

# Copy source
COPY . /var/www/html/

# Set quyền
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080