FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 8002:80 --name sss-web-08_todo-app sss-web-08_todo-app

build: generate
	docker build -f Dockerfile -t sss-web-08_todo-app ..
	rm ../src/flag.php

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/flag-template.php > ../src/flag.php

stop:
	docker stop sss-web-08_todo-app

clean: stop
	docker rm sss-web-08_todo-app

.PHONY: build run stop clean
