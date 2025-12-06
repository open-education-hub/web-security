run: build
	docker run -d -p 8004:80 --name sss-web-08_handy-tool sss-web-08_handy-tool

build:
	docker build -f Dockerfile -t sss-web-08_handy-tool ..

stop:
	docker stop sss-web-08_handy-tool

clean: stop
	docker rm sss-web-08_handy-tool

.PHONY: build run stop clean
