#!/bin/bash

PORT=7002

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.114:'$PORT
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

# Snoop Doggy Dog
echo "Starting exploit for Snoop Doggy Dog"
echo "Downloading the image that contains the flag..."
wget "$URL"'/images/running-dogs-flag.jpg'
