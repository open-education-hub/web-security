FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 8005:80 --name sss-web-08_defaced-website sss-web-08_defaced-website

build: generate
	docker build -f Dockerfile -t sss-web-08_defaced-website ..
	rm ../src/index.php

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/index-template.php > ../src/index.php

stop:
	docker stop sss-web-08_defaced-website

clean: stop
	docker rm sss-web-08_defaced-website

.PHONY: build run stop clean
