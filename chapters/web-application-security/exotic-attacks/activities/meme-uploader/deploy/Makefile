run: build
	docker run -d -p 8003:80 --name sss-web-08_meme-uploader sss-web-08_meme-uploader

build:
	docker build -f Dockerfile -t sss-web-08_meme-uploader ..

stop:
	docker stop sss-web-08_meme-uploader

clean: stop
	docker rm sss-web-08_meme-uploader

.PHONY: build run stop clean
