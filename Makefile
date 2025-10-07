build:
	docker compose build

up:
	docker compose up -d

down:
	docker compose down

logs:
	docker compose logs -f

test:
	docker compose run --rm api ./vendor/bin/phpunit


