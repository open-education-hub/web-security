# Name: 

Web: Exotic Attacks: Breaking Hashes

## Description

Get the flag from [breaking hashes](http://141.85.224.105:30002/). 
Good luck!

## Vulnerability

LFI + loose comparison in PHP

There is an accessible file on the server that contains a relevant piece of the source code.

A hint for the filename can be found in a comment in the page source.

The server does not properly check the username and password (loose PHP comparison is used).

If you input the correct combination of username and password that matches the condition, you get the flag.

## Exploit

Solution in ./sol/solution.sh.
