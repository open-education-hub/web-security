FROM php:7.2-apache

COPY src/ /var/www/html/

COPY flag /var/www/html/flag.txt

RUN ["mkdir", "uploads"]

RUN ["chmod", "a+rwx", "uploads"]
