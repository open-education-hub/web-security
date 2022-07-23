run: build
	docker run -d -p 8001:80 --name sss-web-08_pro-replacer sss-web-08_pro-replacer

build:
	docker build -f Dockerfile -t sss-web-08_pro-replacer ..

stop:
	docker stop sss-web-08_pro-replacer

clean: stop
	docker rm sss-web-08_pro-replacer

.PHONY: build run stop clean
