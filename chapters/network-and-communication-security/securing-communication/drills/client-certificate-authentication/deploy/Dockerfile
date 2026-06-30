FROM debian:12
RUN apt update
RUN apt install -y nginx

ARG HOST
COPY certs/$HOST.crt /etc/ssl/certs/
COPY private/$HOST.key /etc/ssl/private/

COPY nginx-template.conf /etc/nginx/sites-available/$HOST
RUN sed -i 's/__TEMPLATE__'/$HOST/g /etc/nginx/sites-available/$HOST
RUN ln -sfn /etc/nginx/sites-available/$HOST /etc/nginx/sites-enabled/

COPY index.html /var/www/html/

RUN mkdir /etc/nginx/client_certs/
COPY certs/ca.crt /etc/nginx/client_certs/

RUN apt-get -y clean
RUN rm -rf /var/lib/apt/lists/*

COPY ./run.sh /usr/local/bin/
CMD ["/usr/local/bin/run.sh"]

USER root
WORKDIR /root
