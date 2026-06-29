#!/bin/bash

unzip -d . ../support/certs.zip

for i in $(seq 1 100); do
    openssl verify -CAfile ./certs/ca_two.crt ./certs/"$i".crt > /dev/null 2>&1
    if test "$?" -eq 0; then
        openssl x509 -noout -subject -in ./certs/"$i".crt | grep -o 'SSS{.*}'
    fi
done

rm -rf ./certs/ *.crt
