FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_great-names ..

run: build
	docker run -d -p 8080:80 --name sss-web-02_great-names sss-web-02_great-names

stop:
	docker stop sss-web-02_great-names

clean: stop
	docker rm sss-web-02_great-names

.PHONY: build run stop clean
