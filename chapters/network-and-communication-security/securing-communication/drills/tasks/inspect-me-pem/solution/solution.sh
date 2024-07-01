#!/bin/bash

openssl x509 -noout -text -in ../public/example.crt | grep -o 'SSS{.*}'
