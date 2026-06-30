#!/bin/bash
port=31443

if [[ $1 == "local" ]]
then
    host='127.0.0.1'
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    host='141.85.224.102'
elif [[ $# -ne 2 ]]
then
    echo "Usage:"
    echo $0" {local,remote}"
    echo "or"
    echo $0" <host> <port>"
    exit 1
else
    host=$1
    port=$2
fi

curl --cacert ../deploy/certs/ca.crt --cert ../deploy/certs/$host.crt --key ../deploy/private/$host.key https://$host:$port -s | grep SSS

# TODO update the solution to use the files from the archive received by the participants, i.e., create a new client cert from it

pass=sss-web-ca

# unzip -d . ../support/certs.zip
# # openssl rsa -in ./private/ca.key -out ca_decrypted.key
# openssl genrsa -out client.key 2048
# # openssl req -config openssl.cnf -key client.key -subj "/C=RO/ST=Bucharest/L=Bucharest/O=University POLITEHNICA of Bucharest/OU=Computer Science and Engineering Department/CN=client" -new -sha256 -out client.csr
# openssl req -key client.key -subj "/C=RO/ST=Bucharest/L=Bucharest/O=University POLITEHNICA of Bucharest/OU=Computer Science and Engineering Department/CN=client" -new -sha256 -out client.csr
# # yes y | openssl ca -config openssl.cnf -passin pass:sss-web-ca -days 3750 -notext -md sha256 -in client.csr -out client.crt
# yes y | openssl ca -passin pass:sss-web-ca -days 3750 -notext -md sha256 -in client.csr -out client.crt

# openssl req -new -key client.key -out client.csr -subj "/CN=ctf-player"
# openssl x509 -req -days 365 -in client.csr -CA ca.crt -CAkey ca.key -CAcreateserial -out client.crt
# openssl s_client -connect $ip:$port -servername spqr.net -showcerts </dev/null 2>/dev/null | openssl x509 -text -noout | grep SSS
