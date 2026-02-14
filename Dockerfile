FROM php:8.2-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

EXPOSE 8080

RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf