# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/index.template.html /tmp/index.template.html
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/index.template.html > /tmp/index.html

# Stage 2: Final image
FROM php:7.2-apache

COPY --from=builder /tmp/index.html /var/www/html/eyes/
