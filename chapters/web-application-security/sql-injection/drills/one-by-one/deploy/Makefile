FILE    :=../flag
FLAG    :=$(shell cat $(FILE))

run: generate
	docker-compose up -d

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/index.template.php > ../src/index.php

stop:
	docker-compose stop

clean: stop
	docker-compose down -v

.PHONY: generate build run stop clean
