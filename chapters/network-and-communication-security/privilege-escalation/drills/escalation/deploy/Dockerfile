FROM php:7.2-apache as web

# setup web stuff
RUN sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g; s|security.debian.org/debian-security|archive.debian.org/debian-security|g; /buster-updates/d' /etc/apt/sources.list && \
    apt update && apt install -y net-tools vim sudo python3 gdb ltrace && \
    groupadd -g 3000 www && \
    useradd -u 1000 -g www -s /usr/sbin/nologin -d /var/www www && \
    sed -i 's/Options Indexes FollowSymLinks/Options -Indexes/g' /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride all/g' /etc/apache2/apache2.conf && \
    echo ServerName bit-sentinel.com > /etc/apache2/conf-available/fqdn.conf && \
    echo "ServerSignature Off\nServerTokens Prod" > /etc/apache2/conf-available/signature.conf && \
    sed -i "s/80/1234/g" /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf && \
    echo "upload_max_filesize = 1M" > /usr/local/etc/php/conf.d/maxsizes.ini && \
    echo "post_max_size = 5M" >> /usr/local/etc/php/conf.d/maxsizes.ini && \
    echo "memory_limit = 128M" >> /usr/local/etc/php/conf.d/maxsizes.ini && \
    echo "display_errors = Off" >> /usr/local/etc/php/conf.d/maxsizes.ini && \
    echo "html_errors = Off" >> /usr/local/etc/php/conf.d/maxsizes.ini && \
    echo "expose_php = Off" >> /usr/local/etc/php/conf.d/maxsizes.ini && \
    a2enconf fqdn && \
    a2enconf signature && \
    a2enmod rewrite && \
    a2dismod status  && \
    a2enmod -q headers  && \
    a2enmod -q reqtimeout

COPY index.php /var/www/html/index.php
RUN chown root:www /var/www/html
RUN chmod 1775 /var/www/html
RUN chown root /var/www/html/index.php
#RUN mkdir /var/www/html/unbreakable
RUN rm -rf /var/www/html/index.html
RUN ls -la /var/www/html/

RUN mkdir /tmp/apache2 && chown www. /tmp/apache2

# setup the users
RUN groupadd -g 1001 user1 && \
    groupadd -g 1002 user2 && \
    groupadd -g 1003 user3 && \
    groupadd -g 1004 user4 && \
    groupadd -g 1005 user5 && \
    useradd -rm -d /home/user1 -s /bin/bash -g user1 -u 1001 user1 && echo "user1:Unbr3ak@bl3p@SSw0rd3458097345" | chpasswd && \
    useradd -rm -d /home/user2 -s /bin/bash -g user2 -u 1002 user2 && echo "user2:!!cherry!!" | chpasswd && \
    useradd -rm -d /home/user3 -s /bin/bash -g user3 -u 1003 user3 && echo "user3:Unbr3ak@bl3p@SSw0rd12908123" | chpasswd && \
    useradd -rm -d /home/user4 -s /bin/bash -g user4 -u 1004 user4 && echo "user4:Unbr3ak@bl3p@SSw0rd34563453" | chpasswd && \
    useradd -rm -d /home/user5 -s /bin/bash -g user5 -u 1005 user5 && echo "user4:Unbr3ak@bl3p@SSw0rd34563453" | chpasswd

# Privesc from www to user2
COPY --chown=root:www ./note.txt /var/www/html/note.txt
RUN chown root:www /var/www/html/note.txt 
RUN mkdir -p /opt/...
COPY --chown=root:www shadow.bak /opt/.../shadow.bak
RUN chmod -R 750 /opt && \
    chown -R root:www /opt

# Privesc from user2 to user3
COPY --chown=root:root ./sudoers /etc/sudoers

# Privesc from user3 to user4
USER user4
RUN echo "https://www.youtube.com/watch?v=dQw4w9WgXcQ" >> /tmp/flag && \
    chmod 444 /tmp/flag


USER root
COPY ./fixpermissions /usr/bin/fixpermissions
RUN chown user4:user4 /usr/bin/fixpermissions && \
    chmod u+s /usr/bin/fixpermissions && \
    chmod g+s /usr/bin/fixpermissions

#Permissons user5
RUN chown user5:user5 /usr/bin/gdb && \
    chmod u+s /usr/bin/gdb && \
    chmod g+s /usr/bin/gdb

# Adding flags
COPY --chown=root:user2 ./flag1.txt /home/user2/flag1.txt
COPY --chown=root:user3 ./flag2.txt /home/user3/flag2.txt
COPY --chown=root:user4 ./flag3.txt /home/user4/flag3.txt
COPY --chown=root:user5 ./flag4.txt /home/user5/flag4.txt
# Permissions time
RUN chmod -R 750 /home/user2 && \
    chmod -R 750 /home/user3 && \
    chmod -R 750 /home/user4 && \
    chmod -R 750 /home/user5 && \
    chown -R root:user2 /home/user2 && \
    chown -R root:user3 /home/user3 && \
    chown -R root:user4 /home/user4 && \
    chown -R root:user5 /home/user5 && \
    chmod 440 /home/user2/flag1.txt && \
    chmod 440 /home/user3/flag2.txt && \
    chmod 440 /home/user4/flag3.txt && \
    chmod 440 /home/user5/flag4.txt

#USER www
USER www
EXPOSE 80
CMD [ "/usr/sbin/apachectl","-DFOREGROUND","-k","start","-e","debug" ]
