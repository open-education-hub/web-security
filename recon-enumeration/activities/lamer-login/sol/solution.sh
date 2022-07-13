#!/bin/bash
PORT=8001

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    url='http://141.85.224.103:'$PORT
else
    url=$1':'$2
fi

curl $url --data-raw 'username=abel&password=whatever&submit=Login' -s
