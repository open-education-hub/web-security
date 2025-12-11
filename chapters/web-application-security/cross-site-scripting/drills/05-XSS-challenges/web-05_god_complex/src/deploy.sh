#!/usr/bin/env bash

docker stop gc
docker rm gc
docker build -t gc .
docker run --name gc -dp 8084:8080 gc