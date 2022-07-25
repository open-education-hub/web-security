#!/bin/bash

if [ -z "$1"]
then
    echo "No IP supplied"
else
    echo $1
    sshpass -p YvFWPeC7sTWJdaYQ ssh jack@$1 -p 2220
fi