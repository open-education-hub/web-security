FILE    :=../flag
FLAG    :=$(shell cat $(FILE))

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ./index.template.php > ./index.php

.PHONY: generate
