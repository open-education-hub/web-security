#!/bin/bash

part_host=${HOST%%.*}

cat > /etc/hosts <<END
127.0.0.1 $HOST $part_host
END

service nginx start

sleep infinity
