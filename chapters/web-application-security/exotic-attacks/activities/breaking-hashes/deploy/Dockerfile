# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/index-template.php /tmp/index-template.php

RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/index-template.php > /tmp/index.php

# Stage 2: Build the final image
FROM php:7.2-apache

COPY src/healthcheck.php /var/www/html/healthcheck.php
COPY --from=builder /tmp/index.php /var/www/html/
