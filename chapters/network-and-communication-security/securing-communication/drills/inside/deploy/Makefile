.PHONY: build run clean

run: build
	docker run -d -p 3380:80 -p 33443:443 --name sss-web-03_inside -it sss-web-03_inside

build: Dockerfile
	docker build --build-arg HOST="$(HOST)" -t sss-web-03_inside .

clean:
	docker stop sss-web-03_inside
	docker rm -f sss-web-03_inside
