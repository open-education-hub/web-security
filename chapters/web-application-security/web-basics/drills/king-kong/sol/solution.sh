#!/bin/bash
PORT=8086

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# King-Kong
echo "Start exploit for King-Kong"
url=$url'/king-kong/'
flag=$(curl -s -A 'King-Kong' $url | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
