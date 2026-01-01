EXTERNAL_PORT := 8080
INTERNAL_PORT := 80
IMG_NAME := sss-web-01_cockroach
CONT_NAME := $(IMG_NAME)
FLAG := $(shell cat ../flag)

run: build
	docker run -d -p $(EXTERNAL_PORT):$(INTERNAL_PORT) --name $(CONT_NAME) -t $(IMG_NAME)

build:
	docker build --build-arg FLAG=$(FLAG) -t $(IMG_NAME) -f Dockerfile ..

stop:
	docker stop $(CONT_NAME)

clean: stop
	docker rm $(CONT_NAME)
	docker image rm $(IMG_NAME):latest

.PHONY: run build stop clean