build:
	docker build -f Dockerfile -t sss-web-04_nightmare-store ..

run: build
	docker run -d -p 8081:80 --name sss-web-04_nightmare-store sss-web-04_nightmare-store

stop:
	docker stop sss-web-04_nightmare-store

clean: stop
	docker rm sss-web-04_nightmare-store

.PHONY: build run stop clean