#!/bin/bash

flag=$(cat ../flag)

openssl req -x509 -newkey rsa:4096 -sha256 -days 3650 -nodes \
  -keyout example.key -outform der -out example.der -extensions san -config \
  <(echo "[req]";
    echo distinguished_name=req;
    echo "[san]";
    echo subjectAltName=DNS:example.com,DNS:$flag,IP:10.0.0.1
    ) \
  -subj "/CN=example.com"
