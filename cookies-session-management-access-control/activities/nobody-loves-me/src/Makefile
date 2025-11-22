FILE	:=../flag
FLAG	:=$(shell cat $(FILE))

generate:
	rm -rf ../public
	mkdir -p ../public/nobody-loves-me
	sed 's/__TEMPLATE__/$(FLAG)/g' flag.template.php > ../public/nobody-loves-me/flag.php
	cp -r assets ernq-svyr.php index.html ../public/nobody-loves-me

.PHONY: generate
