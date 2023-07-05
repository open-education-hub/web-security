INTERNAL_PORT := 80
CONT_NAME := ${IMG_NAME}
FILE_FLAG := ../flag
FLAG := $(shell cat $(FILE_FLAG))

ifndef CONT_NAME
$(error You need to set a name for IMG_NAME variable(e.g. sss-web-01_activity-name).)
endif

ifndef FILE_TEMPLATE
$(error You need to set a path for FILE_TEMPLATE.)
endif

ifndef FILE_SRC
$(error You need to set a path for FILE_SRC.)
endif

ifndef EXTERNAL_PORT
$(error You need to set EXTERNAL_PORT variable.)
endif

run: generate build
	docker run -d -p $(EXTERNAL_PORT):$(INTERNAL_PORT) --name $(CONT_NAME) -t $(IMG_NAME)

build: generate
	docker build -t $(IMG_NAME) -f Dockerfile ..

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' $(FILE_TEMPLATE) > $(FILE_SRC)

stop:
	docker stop $(CONT_NAME)

clean: stop
	docker rm $(IMG_NAME)
	docker image rm $(IMG_NAME):latest
	rm $(FILE_SRC)

.PHONY: run build generate stop clean
