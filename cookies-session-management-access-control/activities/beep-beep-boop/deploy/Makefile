FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_beep-beep-boop ..

run: build
	docker run -d -p 8088:80 --name sss-web-02_beep-beep-boop sss-web-02_beep-beep-boop

stop:
	docker stop sss-web-02_beep-beep-boop

clean: stop
	docker rm sss-web-02_beep-beep-boop

.PHONY: build run stop clean
