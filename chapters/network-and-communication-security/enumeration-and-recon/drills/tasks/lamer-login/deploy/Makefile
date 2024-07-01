FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 8001:80 --name sss-web-06_lamer-login sss-web-06_lamer-login

build: generate
	docker build -f Dockerfile -t sss-web-06_lamer-login ..

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/index-template.php > ../src/index.php

stop:
	docker stop sss-web-06_lamer-login

clean: stop
	docker rm sss-web-06_lamer-login
	rm ../src/index.php

.PHONY: build run stop clean
