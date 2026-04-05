FROM php:7.4-apache

RUN apt-get update && apt-get install -y libmysqli-dev && \
    docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli
