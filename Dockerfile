FROM php:8.2-apache

# Cài PostgreSQL driver + bật mod_rewrite
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Copy source code vào container
COPY . /var/www/html

# Đổi DocumentRoot sang /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Tạo thư mục images nếu chưa có
RUN mkdir -p /var/www/html/public/images

# Cấp quyền ghi cho thư mục upload
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/public/images

EXPOSE 80