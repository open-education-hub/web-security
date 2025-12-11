docker stop fc
docker rm fc
docker build -t fc .
docker run --name fc -dp 8083:8080 fc