FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        sudo \
    && docker-php-ext-install zip


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./app /var/www/html

RUN cd /var/www/html && composer install

RUN sudo apt-get install poppler-utils -y

RUN chmod -R 777 /usr/bin/pdfinfo && \
    chmod -R 777 /usr/bin/pdftohtml
