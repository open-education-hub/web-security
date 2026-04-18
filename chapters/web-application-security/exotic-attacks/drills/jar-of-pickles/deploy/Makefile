run: build
	docker run -d -p 8007:80 --name sss-web-08_jar-of-pickles sss-web-08_jar-of-pickles

build:
	docker build -f Dockerfile -t sss-web-08_jar-of-pickles ..

stop:
	docker stop sss-web-08_jar-of-pickles

clean: stop
	docker rm sss-web-08_jar-of-pickles

.PHONY: build run stop clean
