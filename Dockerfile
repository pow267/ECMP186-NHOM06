FROM php:8.2-apache

# Cài thư viện cần cho Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Đổi Apache sang port 8080 (Fly yêu cầu)
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/g' /etc/apache2/sites-available/000-default.conf

# Bật rewrite (nếu cần .htaccess)
RUN a2enmod rewrite

# Copy source code
COPY . /var/www/html/

# Set quyền
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080