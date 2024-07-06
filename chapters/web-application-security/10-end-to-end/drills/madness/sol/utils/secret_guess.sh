#!/bin/bash
#  SPDX-License-Identifier: BSD-3-Clause

for i in {0..99}
do
        # modify the ip address below and the hidden directory that you found
        curl --silent http://10.10.94.80/x/?secret=$i | grep right >> /dev/null

        if [ $? -eq 0 ]
        then
                echo "$i is our SECRET page"
                # modify the ip address below and the hidden directory that you found
                curl --silent http://10.10.94.80/x/?secret=$i
                break;
        else
                echo "Secret $i is wrong"
        fi
done
