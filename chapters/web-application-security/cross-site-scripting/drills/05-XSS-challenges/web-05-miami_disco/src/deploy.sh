#!/usr/bin/env bash

docker stop md
docker rm md
docker build -t md .
docker run --name md -dp 8082:8080 md