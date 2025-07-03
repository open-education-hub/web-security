# Name: Chef hacky mchack

## Description

Get the flag from http://141.85.224.115:8087.

Score: 25

## Vulnerability

The flag is displayed only if certain cookies are set.

## Exploit

Script in `./sol/solution.sh`

## Environment

Apache web server. (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.

If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.

It is not possible to update the container without updating the image first.
