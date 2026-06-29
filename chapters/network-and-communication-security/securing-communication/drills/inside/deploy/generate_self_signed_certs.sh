#!/bin/bash

openssl req -x509 -newkey rsa:4096 -sha256 -days 3650 -nodes \
  -keyout pax.imperia.org.key -out pax.imperia.org.crt -extensions san -config \
  <(echo "[req]";
    echo distinguished_name=req;
    echo "[san]";
    echo subjectAltName=DNS:pax.imperia.org,DNS:spqr.net,IP:10.0.0.1
    ) \
  -subj "/CN=pax.imperia.org"

flag=$(cat ../flag)

openssl req -x509 -newkey rsa:4096 -sha256 -days 3650 -nodes \
  -keyout spqr.net.key -out spqr.net.crt -extensions san -config \
  <(echo "[req]";
    echo distinguished_name=req;
    echo "[san]";
    echo subjectAltName=DNS:spqr.net,DNS:$flag,IP:10.0.0.1
    ) \
  -subj "/CN=spqr.net"
