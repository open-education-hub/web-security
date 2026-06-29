#!/bin/bash

cat > /etc/hosts <<END
127.0.0.1 smokey.burger.com smokey
END

service nginx start

sleep infinity
