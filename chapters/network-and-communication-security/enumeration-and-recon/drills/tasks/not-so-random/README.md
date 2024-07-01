# Name: Web: Recon & Enumeration: Not So Random

## Hints

1. Try to find the source code of the application.

## Vulnerability

There is a backup file accessible on the server containg the source code.

## Exploit

Find the `source.bak` file. This tells you that you have to make a request with the query parameter: random_numberrr=<some number>

Enumerate the number until you find the correct one.

Exploit in `./solution/solution.sh`.
