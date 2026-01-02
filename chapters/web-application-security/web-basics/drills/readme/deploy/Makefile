EXTERNAL_PORT := 30013
INTERNAL_PORT := 80
IMG_NAME := sss-web-01_readme
CONT_NAME := ${IMG_NAME}
FLAG_FILE := ../flag

ifndef CONT_NAME
$(error You need to set a name for IMG_NAME variable(e.g. sss-web-01_activity-name).)
endif

ifndef EXTERNAL_PORT
$(error You need to set EXTERNAL_PORT variable.)
endif

run: build
	docker run -d -p $(EXTERNAL_PORT):$(INTERNAL_PORT) --name $(CONT_NAME) -t $(IMG_NAME)

build:
	docker build --build-arg FLAG="$$(cat $(FLAG_FILE))" -t $(IMG_NAME) -f Dockerfile ..

stop:
	docker stop $(CONT_NAME)

clean: stop
	docker rm $(IMG_NAME)
	docker image rm $(IMG_NAME):latest

.PHONY: run build stop clean
