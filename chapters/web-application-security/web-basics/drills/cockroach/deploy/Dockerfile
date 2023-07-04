FROM tiangolo/uwsgi-nginx-flask:python3.8

# copy over our requirements.txt file
COPY src/requirements.txt /tmp/

# upgrade pip and install required python packages
RUN pip3 install --no-cache-dir -r /tmp/requirements.txt && mkdir app

# copy over our app code
COPY src/main.py /app
