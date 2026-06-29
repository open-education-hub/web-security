#!/bin/bash

pushd scripts > /dev/null 2>&1 || exit 1

./create_ca.sh ca_one
./create_ca.sh ca_two

good_flag=$(cat ../../flag)
good_flag_id=$(($RANDOM % 100 + 1))

for i in $(seq 1 100); do
    current_fake_flag='SSS{'$(sed -n "${i}p" ./fake_flags.txt)'}'
    echo "$current_fake_flag"
    if test "$good_flag_id" -ne "$i"; then
        ./create_cert.sh ca_one "$i" "$current_fake_flag"
    fi
done
./create_cert.sh ca_two "$good_flag_id" "$good_flag"

zip ../../support/certs.zip certs/ca_one.crt certs/ca_two.crt certs/*.crt

popd > /dev/null 2>&1 || exit 1
