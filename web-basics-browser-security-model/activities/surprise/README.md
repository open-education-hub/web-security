# Name

Web: Web basics and browser security model: Surprise

## Description

Get the flag from [surprise](http://141.85.224.157:8093/surprise/).
Try to modify an existing resource at this location.

Score: 50

## Vulnerability

The flag is displayed only if the `PUT` method is called with contenty-type `application/json` and a JSON body with the `name` key for the exposed route.

## Exploit

Script in `./sol/solution`.
