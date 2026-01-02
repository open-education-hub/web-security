EXTERNAL_PORT := 30010
INTERNAL_PORT := 80
NAME := web-basics_one-by-one
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
