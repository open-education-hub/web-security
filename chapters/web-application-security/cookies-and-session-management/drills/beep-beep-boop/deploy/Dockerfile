# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/73656372657420666f72204153494d4f.template.php /tmp/73656372657420666f72204153494d4f.template.php
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/73656372657420666f72204153494d4f.template.php > /tmp/73656372657420666f72204153494d4f.php

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.html /var/www/html/
COPY src/robots.txt /var/www/html/
COPY src/assets /var/www/html/assets
COPY --from=builder /tmp/73656372657420666f72204153494d4f.php /var/www/html/
