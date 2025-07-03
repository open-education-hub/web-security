FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public
	cp -r css ../public/
	cp -r img ../public/
	cp -r invoice ../public/
	cp -r js ../public/
	cp -r php-random-name-generator ../public/
	cp -r vendor ../public/
	cp -r index.php ../public/index.php
	sed 's/__TEMPLATE__/$(FLAG)/g' ./invoice.template.php > ../public/invoice.php

.PHONY: generate
