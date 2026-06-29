#!/bin/bash
port=33443

if [[ $1 == "local" ]]
then
    ip='127.0.0.1'
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    ip='141.85.224.117'
elif [[ $# -ne 2 ]]
then
    echo "Usage:"
    echo $0" {local,remote}"
    echo "or"
    echo $0" <ip> <port>"
    exit 1
else
    ip=$1
    port=$2
fi

openssl s_client -connect $ip:$port -servername spqr.net -showcerts </dev/null 2>/dev/null | openssl x509 -text -noout | grep SSS
