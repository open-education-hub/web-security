FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate
	docker-compose up --build -d

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/leaderboard-template.php > ../src/leaderboard.php

stop:
	docker-compose stop

clean: stop
	docker-compose rm
	rm ../src/leaderboard.php

.PHONY: run stop clean
