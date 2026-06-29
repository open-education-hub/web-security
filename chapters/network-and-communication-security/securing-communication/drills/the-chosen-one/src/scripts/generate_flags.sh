#!/bin/bash

cat /etc/dictionaries-common/words | grep -v "'" | head -20000 | shuf | head -100
