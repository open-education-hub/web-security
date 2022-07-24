run: build
	docker run -d -it --hostname=maverick -p 2022:22 --name sss-web-09_maverick sss-web-09_maverick

build:
	docker build -f Dockerfile -t sss-web-09_maverick ..

stop:
	docker stop sss-web-09_maverick

clean: stop
	docker rm sss-web-09_maverick

.PHONY: build run stop clean
