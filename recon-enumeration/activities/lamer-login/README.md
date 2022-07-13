# Name: Lamer Login

## Description

Get the flag from [url]http://ctf-06.security.cs.pub.ro:8001/[/url].

Score: 100

## Vulnerability

Weak credentials, the server tells you if only the username is correct, allowing username enumeration.

## Exploit

Try to bruteforce the usernames using a common usernames wordlist, from GitHub.

After you find the right one, based on what the server responds, try to bruteforce the password using a common passwords wordlist from SecLists (GitHub).

Finally, login.

Exploit in `../sol/solution.sh`.