FILE	:=../flag
FLAG	:=$(shell cat $(FILE))
ROT6_FLAG :=$(shell echo $(FLAG) | tr 'G-ZA-Fg-za-f' 'A-Za-z')

generate:
	rm -rf ../public
	mkdir -p ../public/
	cp -r assets ../public/
	cp *.html ../public
	cp *.php ../public
	rm ../public/admin.template.php
	sed 's/__TEMPLATE__/$(ROT6_FLAG)/g' ./admin.template.php > ../public/admin.php

.PHONY: generate
