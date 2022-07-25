FROM cataiovita/sss_time:latest
RUN apt-get update && apt-get install cron -y
COPY ./start-cron /usr/sbin
COPY ./start-services /usr
ENTRYPOINT ["/bin/sh", "-c", "/usr/start-services"]