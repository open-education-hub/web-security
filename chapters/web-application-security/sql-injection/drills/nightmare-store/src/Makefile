FILE    :=../flag
FLAG    :=$(shell cat $(FILE))

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ./main.template.py > ./main.py

.PHONY: generate
