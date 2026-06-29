#!/bin/bash

if test $# -ne 3; then
    echo "Usage: $0 <ca_name> <cert_name> <flag>" 1>&2
    exit 1
fi

ca_name="$1"
cert_name="$2"
flag="$3"

rm index.*
touch index.txt
touch index.txt.attr

# Create key.
openssl genrsa -out private/"$cert_name".key 2048

# Create certificate request.
openssl req -config openssl_"$ca_name".cnf -key private/"$cert_name".key -subj "/C=RO/ST=Bucharest/L=Bucharest/O=University POLITEHNICA of Bucharest/OU=Computer Science and Engineering Department/CN=$flag" -new -sha256 -out csr/"$cert_name".csr

# Create certificate (with CA).
yes y | openssl ca -config openssl_"$ca_name".cnf -passin pass:sss-web-"$ca_name" -days 375 -notext -md sha256 -in csr/"$cert_name".csr -out certs/"$cert_name".crt

# Verify certificate.
openssl verify -CAfile certs/"$ca_name".crt certs/"$cert_name".crt
