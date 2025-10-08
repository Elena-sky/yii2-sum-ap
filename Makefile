DOCKER_COMPOSE := docker compose
CONTAINER_API := yii2-sum-api

.PHONY: build up down test test-coverage open-coverage-docker

build:
	$(DOCKER_COMPOSE) build

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

test:
	$(DOCKER_COMPOSE) run --rm api codecept run unit

test-coverage:
	$(DOCKER_COMPOSE) run --rm -e XDEBUG_MODE=coverage api codecept run unit --coverage --coverage-html
	@if [ -f tests/_output/coverage/index.html ]; then \
		mv tests/_output/coverage/index.html tests/_output/coverage/summary.html; \
		echo '<!DOCTYPE html>' > tests/_output/coverage/index.html; \
		echo '<html><head><meta charset="utf-8"><title>Coverage Index</title></head><body>' >> tests/_output/coverage/index.html; \
		echo '<h1>Code Coverage - Project Index</h1>' >> tests/_output/coverage/index.html; \
		echo '<ul>' >> tests/_output/coverage/index.html; \
		echo '<li><a href="src/index.html">src</a></li>' >> tests/_output/coverage/index.html; \
		echo '<li><a href="controllers/index.html">controllers</a></li>' >> tests/_output/coverage/index.html; \
		echo '<li><a href="models/index.html">models</a></li>' >> tests/_output/coverage/index.html; \
		echo '<li><a href="summary.html">original summary</a></li>' >> tests/_output/coverage/index.html; \
		echo '</ul>' >> tests/_output/coverage/index.html; \
		echo '</body></html>' >> tests/_output/coverage/index.html; \
	fi

open-coverage-docker:
	$(DOCKER_COMPOSE) run --rm -p 8081:8081 api \
	  php -S 0.0.0.0:8081 -t tests/_output/coverage & \
	sleep 1; open http://localhost:8081
