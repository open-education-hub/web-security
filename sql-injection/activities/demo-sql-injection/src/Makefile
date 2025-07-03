FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/
	cp -r mysql ../public/
	cp *.php ../public/
	sed 's/__TEMPLATE__/$(FLAG)/g' ./mysql/init-db.template.sql > ../public/mysql/init-db.sql
	rm ../public/mysql/init-db.template.sql

.PHONY: generate
