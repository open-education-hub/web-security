.PHONY: build run clean

run: build
	docker run -d -p 3280:80 --name sss-web-03_proper-naming -it sss-web-03_proper-naming

build: Dockerfile
	docker build -t sss-web-03_proper-naming .

clean:
	docker stop sss-web-03_proper-naming
	docker rm -f sss-web-03_proper-naming
