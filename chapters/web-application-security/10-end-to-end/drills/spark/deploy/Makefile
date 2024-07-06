run: build
	docker run -d -it -e SPARK_MODE=master -e SPARK_RPC_AUTHENTICATION_ENABLED=no -e SPARK_RPC_ENCRYPTION_ENABLED=no -e SPARK_LOCAL_STORAGE_ENCRYPTION_ENABLED=no -e SPARK_SSL_ENABLED=no --name sss-web-10_spark -p 8080:8080 sss-web-10_spark

build:
	docker build -f Dockerfile -t sss-web-10_spark ..

stop:
	docker stop sss-web-10_spark

clean: stop
	docker rm sss-web-10_spark

.PHONY: build run stop clean