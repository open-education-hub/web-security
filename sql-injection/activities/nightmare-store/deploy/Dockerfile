FROM tiangolo/uwsgi-nginx-flask:python3.8

COPY ../src/requirements.txt /tmp/
RUN pip3 install -r /tmp/requirements.txt
COPY ../src /app
