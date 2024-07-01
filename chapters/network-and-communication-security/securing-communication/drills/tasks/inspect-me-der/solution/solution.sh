#!/bin/bash

openssl x509 -noout -text -inform der -in ../public/example.der | grep -o 'SSS{.*}'
