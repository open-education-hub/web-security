# Name: Colors

## Description

Get the flag from http://141.85.224.115:8002/colors.

Score: 50

## Vulnerability

The flag can be find for `index=3141`.

## Exploit

Script in `./sol/solution.sh`

## Environment

Apache web server. (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.

If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.

It is not possible to update the container without updating the image first.
