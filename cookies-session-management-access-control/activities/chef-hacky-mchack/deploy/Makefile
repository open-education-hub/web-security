FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_check-hacky-mchack ..

run: build
	docker run -d -p 8087:80 --name sss-web-02_check-hacky-mchack sss-web-02_check-hacky-mchack

stop:
	docker stop sss-web-02_check-hacky-mchack

clean: stop
	docker rm sss-web-02_check-hacky-mchack

.PHONY: build run stop clean
