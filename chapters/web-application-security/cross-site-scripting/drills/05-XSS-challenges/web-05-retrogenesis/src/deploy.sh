docker stop retro
docker rm retro
docker build -t retro .
docker run --name retro -dp 8081:8080 retro