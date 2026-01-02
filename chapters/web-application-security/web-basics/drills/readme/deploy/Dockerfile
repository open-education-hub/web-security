FROM php:7.2-apache AS builder

ARG FLAG
COPY src/index.template.html /tmp/index.template.html
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/index.template.html > /tmp/index.html

FROM php:7.2-apache

COPY --from=builder /tmp/index.html /var/www/html/readme/index.html
