FROM debian:12
RUN apt update
RUN apt install -y nginx

COPY spqr.net.crt /etc/ssl/certs/
COPY spqr.net.key /etc/ssl/private/

COPY pax.imperia.org.crt /etc/ssl/certs/
COPY pax.imperia.org.key /etc/ssl/private/

COPY spqr.net.conf /etc/nginx/sites-available/spqr.net.conf
COPY pax.imperia.org.conf /etc/nginx/sites-available/pax.imperia.org.conf

RUN rm /etc/nginx/sites-enabled/default
RUN ln -sfn /etc/nginx/sites-available/spqr.net.conf /etc/nginx/sites-enabled/
RUN ln -sfn /etc/nginx/sites-available/pax.imperia.org.conf /etc/nginx/sites-enabled/

COPY spqr.net/html/index.html /var/www/spqr.net/html/index.html
COPY pax.imperia.org/html/index.html /var/www/pax.imperia.org/html/index.html

COPY ./run.sh /usr/local/bin/
CMD ["/usr/local/bin/run.sh"]

USER root
WORKDIR /root
