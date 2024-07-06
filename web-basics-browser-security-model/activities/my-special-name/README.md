# Name

Web: Web basics and browser security model: My Special Name

## Description

Get the flag from [special-name](http://141.85.224.157:80/my-special-name).
Retrieve all the names and you will get the flag.
Use the **name-id** parameter.

Score: 50

## Vulnerability

The flag is displayed only if the `GET` method with `name-id` as query parameter is called for the exposed route.
You must give it values between 0 and 100 to find the flag.

## Exploit

Script in `./sol/solution`.
