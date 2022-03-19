FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/
	cp -r assets ../public/
	cp -r dist ../public/
	sed 's/__TEMPLATE__/$(FLAG)/g' ./index.template.php > ../public/index.php

.PHONY: generate
