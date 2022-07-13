Name
----

Web: Framework & API Vulnerabilities: Clean up

Description
-----------

Get the flag from [url]http://141.85.224.114:7004/[/url].

Score: 100

Vulnerability
-------------

Another API version with broken authorization.

Exploit
-------

View the page source. Notice the ajax request made to `/api-v3/get-user-records.php`.
Repeat it in the browser, then also request `/api-v1/get-user-records.php`.
Here, in the response, search (CTRL-F) the flag (the string `SSS`).

Exploit in `solution.py`.
