FILE	:=../flag
FLAG	:=$(shell cat $(FILE))
BASE64_FLAG := $(shell base64 $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/
	cp -r earth ../public/
	cp -r planetarium ../public/
	cp -r index.php ../public/

.PHONY: generate
