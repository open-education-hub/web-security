FROM python:3.8

WORKDIR /home/ctf

RUN pip3 install flask
RUN apt-get update && apt-get -y install netcat

COPY src/ .

CMD ["python", "app.py"]
