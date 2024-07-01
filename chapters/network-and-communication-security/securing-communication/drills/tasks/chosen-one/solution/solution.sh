#!/bin/bash

for i in $(seq 1 100); do
    openssl verify -CAfile ../public/ca_two.crt ../public/"$i".crt > /dev/null 2>&1
    if test "$?" -eq 0; then
        openssl x509 -noout -subject -in ../public/"$i".crt | grep -o 'SSS{.*}'
    fi
done
