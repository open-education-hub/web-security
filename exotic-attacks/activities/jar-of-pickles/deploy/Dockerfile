FROM python:3.8

WORKDIR /home/ctf

RUN pip install flask
RUN apt-get update && apt-get -y install netcat

COPY src/ .

COPY flag flag.txt

CMD ["python", "app.py"]
