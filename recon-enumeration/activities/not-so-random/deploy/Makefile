FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 8000:80 --name sss-web-06_not-so-random sss-web-06_not-so-random

build: generate
	docker build -f Dockerfile -t sss-web-06_not-so-random ..
	rm ../src/index.php

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/index-template.php > ../src/index.php

stop:
	docker stop sss-web-06_not-so-random

clean: stop
	docker rm sss-web-06_not-so-random

.PHONY: build run stop clean
