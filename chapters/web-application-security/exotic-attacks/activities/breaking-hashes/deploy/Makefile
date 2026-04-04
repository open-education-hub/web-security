EXTERNAL_PORT := 30002
INTERNAL_PORT := 80
NAME := sss-web-08_breaking-hashes

FILE := ../flag
FLAG := $(shell cat $(FILE))

run: build
	docker run -d -p $(EXTERNAL_PORT):$(INTERNAL_PORT) --name $(NAME) -t $(NAME)

build:
	docker build --build-arg FLAG=$(FLAG) -t $(NAME) -f Dockerfile ..

stop:
	docker stop $(NAME)

clean: stop
	docker rm $(NAME)
	docker image rm $(NAME):latest


.PHONY: build run stop clean 
