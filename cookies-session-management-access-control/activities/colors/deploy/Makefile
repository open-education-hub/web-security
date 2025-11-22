FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_colors ..

run: build
	docker run -d -p 8082:80 --name sss-web-02_colors sss-web-02_colors

stop:
	docker stop sss-web-02_colors

clean: stop
	docker rm sss-web-02_colors

.PHONY: build run stop clean
