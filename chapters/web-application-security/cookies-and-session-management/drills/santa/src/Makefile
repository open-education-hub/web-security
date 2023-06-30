FILE	:=../flag
FLAG	:=$(shell cat $(FILE))
BASE64_FLAG := $(shell base64 $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/santa
	cp -r assets ../public/santa
	cp -r index.html ../public/santa
	sed 's/__TEMPLATE__/$(BASE64_FLAG)/g' ./assets/js/main.template.js > ../public/santa/assets/js/main.js

.PHONY: generate
