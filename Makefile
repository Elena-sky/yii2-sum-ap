build:
	docker compose build

up:
	docker compose up -d

down:
	docker compose down

logs:
	docker compose logs -f

test:
	./vendor/bin/codecept run unit

test-coverage:
	./vendor/bin/codecept run unit --coverage --coverage-html

test-docker:
	docker compose run --rm api ./vendor/bin/codecept run unit

test-docker-coverage:
	docker compose run --rm api ./vendor/bin/codecept run unit --coverage --coverage-html

clean:
	./vendor/bin/codecept clean
