FROM nginx

COPY ./docker/nginx/config/nginx.conf /etc/nginx/
COPY ./docker/nginx/sites /etc/nginx/sites-available
COPY ./app /var/www/html

RUN apt update \
    && apt upgrade -y\
    && adduser -D -H -u 1000 -s /bin/bash www-data -G www-data

ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000

RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf \
    && rm /etc/nginx/conf.d/default.conf

EXPOSE 80 443

CMD service nginx start