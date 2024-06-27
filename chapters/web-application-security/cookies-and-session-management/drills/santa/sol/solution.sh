#!/bin/bash
PORT=8083

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.115:'$PORT
else
    url=$1':'$2
fi

# Santa
echo "Start exploit for Santa"
url=$url'/santa/'
flag=$(curl -s $url'assets/js/main.js' | grep -o "atob(".*")")
flag=$(echo -n ${flag:6:48} | base64 -d)
echo "Flag is $flag"
echo "----------------------------"
