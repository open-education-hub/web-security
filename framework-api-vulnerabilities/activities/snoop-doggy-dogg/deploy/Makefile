FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: build
	docker run -d -p 7002:80 --name sss-web-07_snoop-doggy-dogg sss-web-07_snoop-doggy-dogg

build:
	docker build -f Dockerfile -t sss-web-07_snoop-doggy-dogg ..

stop:
	docker stop sss-web-07_snoop-doggy-dogg

clean: stop
	docker rm sss-web-07_snoop-doggy-dogg

.PHONY: build run stop clean
