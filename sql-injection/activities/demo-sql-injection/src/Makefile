FILE    :=../flag
FLAG    :=$(shell cat $(FILE))

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ./mysql/init-db.template.sql > ./mysql/init-db.sql

.PHONY: generate
