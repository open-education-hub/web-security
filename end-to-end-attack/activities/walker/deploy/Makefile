run: build
	docker run -d -it --hostname=walker -p 8080:80 --name sss-web-10_walker sss-web-10_walker

build:
	docker build -f Dockerfile -t sss-web-10_walker ..

stop:
	docker stop sss-web-10_walker

clean: stop
	docker rm sss-web-10_walker

.PHONY: build run stop clean