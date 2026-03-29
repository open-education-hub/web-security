# Name: One by one

## Description

Get those yummy sărmăluțe from http://ctf-18.security.cs.pub.ro:8010/.

Score: 100

## Vulnerability

Web is vulnerable to blind SQL injection.

## Exploit

`Promo code` input is vulnerable to blind SQL injection.
The flag can be found by bruteforce the `Promo code` with the same format as usual.

## Environment

Web server with PHP and MYSQL support. (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.
If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.
It is not possible to update the container without updating the image first.
