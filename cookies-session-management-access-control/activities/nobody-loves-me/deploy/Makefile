FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

build:
	docker build -f Dockerfile -t sss-web-02_nobody-loves-me ..

run: build
	docker run -d -p 8081:80 --name sss-web-02_nobody-loves-me sss-web-02_nobody-loves-me

stop:
	docker stop sss-web-02_nobody-loves-me

clean: stop
	docker rm sss-web-02_nobody-loves-me

.PHONY: build run stop clean
