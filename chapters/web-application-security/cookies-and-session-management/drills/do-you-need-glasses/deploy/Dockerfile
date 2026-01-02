# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/admin.template.php /tmp/admin.template.php
RUN ROT13_FLAG="$(printf '%s' "$FLAG" | tr 'A-Za-z' 'N-ZA-Mn-za-m')" && \
  sed "s/__TEMPLATE__/${ROT13_FLAG}/g" /tmp/admin.template.php > /tmp/admin.php

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.html /var/www/html/
COPY src/inner-page.html /var/www/html/
COPY src/staff.html /var/www/html/
COPY src/staff.php /var/www/html/
COPY src/login.php /var/www/html/
COPY src/assets /var/www/html/assets
COPY --from=builder /tmp/admin.php /var/www/html/
