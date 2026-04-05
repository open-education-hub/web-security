run: build
	docker-compose up -d
	
build:
	cd ../src && $(MAKE) generate
	docker-compose build

stop:
	docker-compose stop

clean: stop
	docker-compose down -v

.PHONY: build run stop clean
