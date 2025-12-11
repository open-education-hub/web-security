Challenge connected to reflected XSS.



Name: Web: Challenges: Tactical Precision Dissaray

Description
-----------
A simple challenge for reflected XSS, that should be resolved with only the browser (without console) and with some imagination.

Hint: try some get parameters, guess what they are.

No instrument or programming should be needed to solve this challenge.


Score: 25

Vulnerability
-------------

Call the server url with the GET parameter field1="<script>alert(document.cookie)</script>"
Post request with the resulting page.


Exploit
-------

No need for one it can be  tried easily manually.

Environment
-----------

django and python 

Deploy
------

A simple command should suffice.