FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate build
	docker run -d -p 7004:80 --name sss-web-07_clean-up sss-web-07_clean-up

build: generate
	docker build -f Dockerfile -t sss-web-07_clean-up ..

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/data/users/user-information-expanded-template.json > ../src/data/users/user-information-expanded.json

stop:
	docker stop sss-web-07_clean-up

clean: stop
	docker rm sss-web-07_clean-up
	rm ../src/data/users/user-information-expanded.json

.PHONY: build run stop clean
