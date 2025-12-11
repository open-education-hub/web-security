#!/usr/bin/env bash

docker stop tpd
docker rm tpd
docker build -t tpd .
docker run --name tpd -dp 8080:8080 tpd