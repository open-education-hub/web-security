FROM debian:12
RUN apt update
RUN apt install -y nginx

COPY smokey.burger.com.conf /etc/nginx/sites-available/
RUN ln -sfn /etc/nginx/sites-available/smokey.burger.com.conf /etc/nginx/sites-enabled/

COPY index.html /var/www/html/

RUN mkdir -p /var/www/smokey.burger.com/html/
COPY smokey.burger.com/html/index.html /var/www/smokey.burger.com/html/

COPY ./run.sh /usr/local/bin/
CMD ["/usr/local/bin/run.sh"]

USER root
WORKDIR /root
