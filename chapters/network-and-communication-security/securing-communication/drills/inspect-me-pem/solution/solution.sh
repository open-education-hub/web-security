#!/bin/bash

openssl x509 -noout -text -in ../support/example.crt | grep -o 'SSS{.*}'
