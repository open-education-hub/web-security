# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/consume.template.php /tmp/consume.template.php
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/consume.template.php > /tmp/consume.php

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.php /var/www/html/produce-consume/
COPY src/produce.php /var/www/html/produce-consume/
COPY --from=builder /tmp/consume.php /var/www/html/produce-consume/
