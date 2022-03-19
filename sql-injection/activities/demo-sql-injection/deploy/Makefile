build:
	docker-compose build

run: build
	docker-compose up -d

stop:
	docker-compose stop

clean: stop
	docker-compose down

.PHONY: build run stop clean
