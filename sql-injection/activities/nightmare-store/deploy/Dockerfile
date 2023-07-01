FROM tiangolo/uwsgi-nginx-flask:python3.8

# copy over our requirements.txt file
COPY public/requirements.txt /tmp/

# upgrade pip and install required python packages
RUN pip3 install -r /tmp/requirements.txt

# copy over our app code
COPY public /app

