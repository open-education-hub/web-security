FILE	:= ../flag
FLAG	:= $(shell cat $(FILE))

run: generate
	docker-compose up --build -d
	rm ../src/leaderboard.php

generate:
	sed 's/__TEMPLATE__/$(FLAG)/g' ../src/leaderboard-template.php > ../src/leaderboard.php

stop:
	docker-compose stop

clean: stop
	docker-compose rm

.PHONY: run stop clean
