#!/bin/bash

if test $# -ne 1; then
    echo "Usage: $0 <ca_name>"
    exit 1
fi

ca_name="$1"

if test ! -d certs; then
    mkdir certs
fi

if test ! -d crl; then
    mkdir crl
fi

if test ! -d csr; then
    mkdir csr
fi

if test ! -d newcerts; then
    mkdir newcerts
fi

if test ! -d private; then
    mkdir private
fi

touch index.txt
echo 1000 > serial

# Create root key.
openssl genrsa -aes256 -out private/"$ca_name".key -passout pass:sss-web-"$ca_name" 4096

# Create root certificate.
openssl req -config openssl.cnf -key private/"$ca_name".key -passin pass:sss-web-"$ca_name" -subj "/C=RO/ST=Bucharest/L=Bucharest/O=University POLITEHNICA of Bucharest/OU=Computer Science and Engineering Department/CN=ca.security.cs.pub.ro" -new -x509 -days 7300 -sha256 -extensions v3_ca -out certs/"$ca_name".crt
