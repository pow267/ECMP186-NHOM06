# ==========================================
# STAGE 1: Build Tailwind CSS
# ==========================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package*.json ./
RUN npm install

# Copy Tailwind source files
COPY public ./public
COPY postcss.config.js ./

# Build CSS
RUN npx postcss public/assets/css/input.css \
    -o public/assets/css/output.css


# ==========================================
# STAGE 2: PHP + Apache
# ==========================================
FROM php:8.2-apache

# ===== SYSTEM DEPENDENCIES =====
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libpq-dev \
        curl \
    && docker-php-ext-install pdo pdo_pgsql opcache \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ===== OPcache (DEV AUTO UPDATE) =====
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini

# ===== APACHE CONFIG =====
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy PHP source
COPY . .

# Copy built CSS từ stage 1
COPY --from=frontend-builder /app/public/assets/css/output.css \
    /var/www/html/public/assets/css/output.css

# Remove docker folder khỏi image
RUN rm -rf /var/www/html/docker

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Security
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf && \
    echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

EXPOSE 80

CMD ["apache2-foreground"]