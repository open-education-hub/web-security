# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/invoice.template.php /tmp/invoice.template.php
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/invoice.template.php > /tmp/invoice.php

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.php /var/www/html/
COPY src/css /var/www/html/css
COPY src/js /var/www/html/js
COPY src/img /var/www/html/img
COPY src/invoice /var/www/html/invoice
COPY src/php-random-name-generator /var/www/html/php-random-name-generator
COPY src/vendor /var/www/html/vendor
COPY --from=builder /tmp/invoice.php /var/www/html/
