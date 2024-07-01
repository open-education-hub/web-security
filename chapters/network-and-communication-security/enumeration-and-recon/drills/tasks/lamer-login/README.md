# Name: Web: Recon & Enumeration: Lamer Login

## Vulnerability

Weak credentials, the server tells you if only the username is correct, allowing username enumeration.

## Exploit

Try to bruteforce the usernames using a common usernames wordlist, from GitHub.

After you find the right one, based on what the server responds, try to bruteforce the password using a common passwords wordlist from SecLists (GitHub).

Finally, login.

Exploit in `./solution/solution.sh`.
