FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public
	cp -r assets/ ../public
	cp index.html ../public
	cp robots.txt ../public
	sed 's/__TEMPLATE__/$(FLAG)/g' ./73656372657420666f72204153494d4f.template.php > ../public/73656372657420666f72204153494d4f.php

.PHONY: generate
