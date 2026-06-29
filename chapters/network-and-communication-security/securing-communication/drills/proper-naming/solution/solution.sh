#!/bin/bash
PORT=3280

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    url='http://141.85.224.117:'$PORT
elif [[ $# -ne 2 ]]
then
    echo "Usage:"
    echo $0" {local,remote}"
    echo "or"
    echo $0" <ip> <port>"
    exit 1
else
    url=$1':'$2
fi

curl -s -H "Host: smokey.burger.com" $url | grep SSS
