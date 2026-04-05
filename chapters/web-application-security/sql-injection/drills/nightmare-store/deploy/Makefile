run: build
	docker run -d -p 8081:80 --name sss-web-sql-injection_nightmare-store sss-web-sql-injection_nightmare-store
	
build:
	cd ../src && $(MAKE) generate
	docker build -f Dockerfile -t sss-web-sql-injection_nightmare-store ..

stop:
	docker stop sss-web-sql-injection_nightmare-store

clean: stop
	docker rm sss-web-sql-injection_nightmare-store

.PHONY: build run stop clean
