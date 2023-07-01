FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/
	cp -r static ../public/
	cp -r templates ../public/
	cp requirements.txt ../public/
	cp sample.db ../public/
	sed 's/__TEMPLATE__/$(FLAG)/g' ./main.template.py > ../public/main.py

.PHONY: generate
