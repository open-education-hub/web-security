FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_santa ..

run: build
	docker run -d -p 8083:80 --name sss-web-02_santa sss-web-02_santa

stop:
	docker stop sss-web-02_santa

clean: stop
	docker rm sss-web-02_santa

.PHONY: build run stop clean
