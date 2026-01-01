# Stage 1: Generate the source file with the flag
FROM alpine:latest AS builder

ARG FLAG
COPY src/main.template.py /tmp/main.template.py
RUN sed "s/__TEMPLATE__/${FLAG}/g" /tmp/main.template.py > /tmp/main.py

# Stage 2: Final image
FROM tiangolo/uwsgi-nginx-flask:python3.8

# copy over our requirements.txt file
COPY src/requirements.txt /tmp/

# upgrade pip and install required python packages
RUN pip3 install --no-cache-dir -r /tmp/requirements.txt && mkdir app

# copy the generated source file from builder stage
COPY --from=builder /tmp/main.py /app
