stop:
	docker-compose stop
shell:
	docker-compose exec server /bin/bash
start:
	docker-compose up --detach
destroy:
	docker-compose down --volumes
build:
	docker-compose up --detach --build
