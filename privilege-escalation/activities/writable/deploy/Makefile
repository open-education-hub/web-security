run: build
	docker run -d -it --hostname=writable -p 2022:22 --name sss-web-09_writable sss-web-09_writable

build:
	docker build -f Dockerfile -t sss-web-09_writable ..

stop:
	docker stop sss-web-09_writable

clean: stop
	docker rm sss-web-09_writable

.PHONY: build run stop clean
