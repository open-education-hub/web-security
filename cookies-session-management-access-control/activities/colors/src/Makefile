FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/colors
	sed 's/__TEMPLATE__/$(FLAG)/g' index.template.php > ../public/colors/index.php

.PHONY: generate
