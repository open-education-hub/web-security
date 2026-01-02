EXTERNAL_PORT := 30011
INTERNAL_PORT := 80
NAME := web-basics_produce-consume
FLAG := $(shell cat ../flag)

run: build
	docker run -d -p $(EXTERNAL_PORT):$(INTERNAL_PORT) --name $(NAME) -t $(NAME)

build:
	docker build --build-arg FLAG=$(FLAG) -t $(NAME) -f Dockerfile ..

stop:
	docker stop $(NAME)

clean: stop
	docker rm $(NAME)
	docker image rm $(NAME):latest

.PHONY: run build stop clean
