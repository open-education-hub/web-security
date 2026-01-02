# Stage 1: Generate the source file with the flag (base64 encoded)
FROM alpine:latest AS builder

ARG FLAG
COPY src/assets/js/main.template.js /tmp/main.template.js
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/main.template.js > /tmp/main.js

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.html /var/www/html/santa/
COPY src/assets /var/www/html/santa/assets
COPY --from=builder /tmp/main.js /var/www/html/santa/assets/js/main.js
