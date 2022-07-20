# Name: Defaced Website

## Vulnerability

Source code disclosure + loose comparison in PHP

## Exploit

Inspect the requests made when accessing the website.

Notice that it tries to load `/img/d3f4c3d.png` but receives 404 Not Found.

Instead you can try to access `/img/defaced.png`, now this image exists and it is a capture of a piece from the source code.

The server computes the md5 hash of the string formed by concatenating the username and password.
If it is equal (using loose comparison) to `0e413229387827631581229643338212`, it displays the flag.

From the table in the session content, we know that the md5 hash of the string `QNKCDZO` is `0e830400451993494058024219903391`, which would equal to `0e413229387827631581229643338212`.

The final payload in POST data is:

`username=QNKCDZO&password=&submit=Login`

Exploit in `../sol/solution.sh`.
