FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/
	cp -r assets ../public/
	cp index.php ../public
	sed 's/__TEMPLATE__/$(FLAG)/g' ./manage.template.php > ../public/manage.php

.PHONY: generate
