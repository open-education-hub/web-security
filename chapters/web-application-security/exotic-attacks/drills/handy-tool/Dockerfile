FROM php:8.2-apache

RUN apt-get update && apt-get install -y zip && rm -rf /var/lib/apt/lists/*

COPY src/ /var/www/html/

WORKDIR /var/www/html/

RUN zip -r backup.zip index_to_zip.php
RUN rm index_to_zip.php

COPY flag /home/ctf/flag.txt
