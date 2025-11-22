FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_traverse-universe ..

run: build
	docker run -d -p 8084:80 --name sss-web-02_traverse-universe sss-web-02_traverse-universe

stop:
	docker stop sss-web-02_traverse-universe

clean: stop
	docker rm sss-web-02_traverse-universe

.PHONY: build run stop clean
