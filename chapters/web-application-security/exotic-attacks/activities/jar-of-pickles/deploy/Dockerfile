# Stage 1: Generate the source file with the flag

WORKDIR /home/ctf

ARG FLAG
COPY src/main.template.py /tmp/main.template.py
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/main.template.py > /tmp/main.py

# Stage 2: Final image
FROM tiangolo/uwsgi-nginx-flask:python3.8

RUN pip install flask
RUN apt-get update && apt-get -y install netcat

COPY src/ .

COPY flag flag.txt

CMD ["python", "app.py"]
