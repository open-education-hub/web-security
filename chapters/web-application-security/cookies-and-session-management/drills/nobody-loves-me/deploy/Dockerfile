# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/flag.template.php /tmp/flag.template.php
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/flag.template.php > /tmp/flag.php

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.html /var/www/html/
COPY src/ernq-svyr.php /var/www/html/
COPY src/assets /var/www/html/assets
COPY --from=builder /tmp/flag.php /var/www/html/
