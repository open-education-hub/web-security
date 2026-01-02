# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/manage.template.php /tmp/manage.template.php
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/manage.template.php > /tmp/manage.php

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.php /var/www/html/
COPY src/assets /var/www/html/assets
COPY --from=builder /tmp/manage.php /var/www/html/
