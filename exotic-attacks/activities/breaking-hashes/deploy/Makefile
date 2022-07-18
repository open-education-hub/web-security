FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 8000:80 --name sss-web-08_breaking-hashes sss-web-08_breaking-hashes

build: generate
	docker build -f Dockerfile -t sss-web-08_breaking-hashes ..
	rm ../src/index.php

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/index-template.php > ../src/index.php

stop:
	docker stop sss-web-08_breaking-hashes

clean: stop
	docker rm sss-web-08_breaking-hashes

.PHONY: build run stop clean
