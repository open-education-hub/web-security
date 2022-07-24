run: build
	docker run -d -it --hostname=time -p 2022:22 --name sss-web-09_time sss-web-09_time

build:
	docker build -f Dockerfile -t sss-web-09_time ..

stop:
	docker stop sss-web-09_time

clean: stop
	docker rm sss-web-09_time

.PHONY: build run stop clean
