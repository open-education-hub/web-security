FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public
	sed 's/__TEMPLATE__/$(FLAG)/g' index.template.php > ../public/index.php

.PHONY: generate
