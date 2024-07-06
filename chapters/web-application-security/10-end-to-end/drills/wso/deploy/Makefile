run: build
	docker run -d -it -p 8280:8280 -p 8243:8243 -p 9443:9443 --name sss-web-10_wso sss-web-10_wso
build:
	docker build -f Dockerfile -t sss-web-10_wso ..

stop:
	docker stop sss-web-10_wso

clean: stop
	docker rm sss-web-10_wso

.PHONY: build run stop clean