FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_do-you-need-glasses ..

run: build
	docker run -d -p 8086:80 --name sss-web-02_do-you-need-glasses sss-web-02_do-you-need-glasses

stop:
	docker stop sss-web-02_do-you-need-glasses

clean: stop
	docker rm sss-web-02_do-you-need-glasses

.PHONY: build run stop clean
