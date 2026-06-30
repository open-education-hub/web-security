HOST ?= 141.85.224.102
# HOST ?= 127.0.0.1

.PHONY: build run clean

run: build
	docker run -d -e HOST="$(HOST)" --hostname "$(HOST)" -p 3180:80 -p 31443:443 --name sss-web-03_client-certificate-auth -it sss-web-03_client-certificate-auth

build: Dockerfile
	docker build --build-arg HOST="$(HOST)" -t sss-web-03_client-certificate-auth .

regenerate-client-certs:
	./create-cert ${HOST}
	zip ../support/public.zip certs/ca.crt private/ca.key openssl.cnf

regenerate-ca-certs:
	./create-ca
	zip ../support/public.zip certs/ca.crt private/ca.key openssl.cnf

regenerate-all-certs:
	make regenerate-ca-certs
	make regenerate-client-certs

clean:
	docker stop sss-web-03_client-certificate-auth
	docker rm -f sss-web-03_client-certificate-auth
