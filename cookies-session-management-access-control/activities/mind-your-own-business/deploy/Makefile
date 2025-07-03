FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_mind-your-own-business ..

run: build
	docker run -d -p 8085:80 --name sss-web-02_mind-your-own-business sss-web-02_mind-your-own-business

stop:
	docker stop sss-web-02_mind-your-own-business

clean: stop
	docker rm sss-web-02_mind-your-own-business

.PHONY: build run stop clean
