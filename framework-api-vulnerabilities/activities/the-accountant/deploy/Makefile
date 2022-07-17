FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 7005:80 --name sss-web-07_the-accountant sss-web-07_the-accountant

build: generate
	docker build -f Dockerfile -t sss-web-07_the-accountant ..
	rm ../src/data/retailers/altex.json

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/data/retailers/altex-template.json > ../src/data/retailers/altex.json

stop:
	docker stop sss-web-07_the-accountant

clean: stop
	docker rm sss-web-07_the-accountant

.PHONY: build run stop clean
