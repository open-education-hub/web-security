FROM php:7.4-apache as web

RUN apt-get update -y && apt-get install -y  \
	mariadb-client \
	unzip

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
