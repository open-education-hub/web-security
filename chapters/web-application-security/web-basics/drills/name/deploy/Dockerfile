# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/the_flag.template.html /tmp/the_flag.template.html
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/the_flag.template.html > /tmp/the_flag.html

# Stage 2: Final image
FROM php:7.2-apache

COPY src/index.html /var/www/html/name/
COPY --from=builder /tmp/the_flag.html /var/www/html/name/
