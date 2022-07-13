Name
----

Web: Framework & API Vulnerabilities: The Accountant

Description
-----------

Get the flag from [url]http://141.85.224.114:7005/[/url].

Score: 100

Vulnerability
-------------

Broken access control

Exploit
-------

View the request that is being made when you select a retailer from the dropdown:

`GET /api-v2/retailers/records.php?retailer=emag`

Inspect the source and see that there are hidden retailers (`flanco`).

The flag is retrieved if you guess and make a request for the `altex` retailer.

(CTRL-F in the response text)

Exploit in `solution.py`.
