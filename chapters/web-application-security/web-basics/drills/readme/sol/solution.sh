#!/bin/bash
PORT=8092

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Readme
echo "Start exploit for Readme"
url=$url'/readme/'
flag=$(curl -s $url | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
