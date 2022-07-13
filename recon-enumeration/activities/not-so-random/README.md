# Name: Not So Random

## Description

Get the flag from [url]http://ctf-06.security.cs.pub.ro:8000/[/url].

Score: 150

## Hints

1. Try to find the source code of the application.

## Vulnerability

There is a backup file accessible on the server containg the source code.

## Exploit

Find the `source.bak` file. This tells you that you have to make a request with the query parameter: random_numberrr=<some number>

Enumerate the number until you find the correct one.

Exploit in `../sol/solution.sh`.
