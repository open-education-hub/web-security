# Name: Web: Exotic Attacks: Breaking Hashes

## Vulnerability

LFI + loose comparison in PHP

There is an accessible file on the server that contains a relevant piece of the source code.

A hint for the filename can be found in a comment in the page source.

The server does not properly check the username and password (loose PHP comparison is used).

If you input the correct combination of username and password that matches the condition, you get the flag.

## Exploit

Inspect the source page and see this comment: `<!-- TODO: Remove source.phar -->`.

Request the resource `/source.bak` and download the file. Inspect its contents.

You notice that you have to find an username and a password that are not equal (in the context of PHP loose comparison), but their sha256 hashes are the same.

Since collisions in sha256 hashes are not known, we have to work with the username and password.

We can pass the parameters as arrays and make one of the values a string, and the other the integer equivalent.

A possible payload in POST data is:

`username[]="8"&password[]=8&submit=Login`

Exploit in `../sol/solution.sh`.
