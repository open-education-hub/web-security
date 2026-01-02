EXTERNAL_PORT := 8083
INTERNAL_PORT := 80
NAME := cookies-and-session-management_santa
BASE64_FLAG := $(shell base64 -i ../flag)

run: build
	docker run -d -p $(EXTERNAL_PORT):$(INTERNAL_PORT) --name $(NAME) -t $(NAME)

build:
	docker build --build-arg FLAG="$(BASE64_FLAG)" -t $(NAME) -f Dockerfile ..

stop:
	docker stop $(NAME)

clean: stop
	docker rm $(NAME)
	docker image rm $(NAME):latest

.PHONY: run build stop clean
