# Name: Demo

## Description

This is a simple demo that will be used as an example in the session.

Similar, but not the same. Can you find the name of the bastard?
Check this out: http://ctf-18.security.cs.pub.ro:8083/

Score: 25

## Vulnerability

SQL injection vulnerability.

## Exploit

Search in the `flags` table for the flag.

## Environment

Web server with PHP and MYSQL support. (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.
If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.
It is not possible to update the container without updating the image first.
