COMPOSE := docker compose
APP_ENV := NexusGear/.env
DOCKER_ENV := NexusGear/.env.docker.example
export APP_PORT ?= 8080
export APP_URL ?= http://localhost:$(APP_PORT)
export VITE_PORT ?= 5173
export MAILPIT_PORT ?= 8025
TEST_ENV := -e APP_ENV=testing
TEST_ENV += -e APP_MAINTENANCE_DRIVER=file
TEST_ENV += -e BCRYPT_ROUNDS=4
TEST_ENV += -e BROADCAST_CONNECTION=null
TEST_ENV += -e CACHE_STORE=array
TEST_ENV += -e DB_CONNECTION=sqlite
TEST_ENV += -e DB_DATABASE=:memory:
TEST_ENV += -e DB_URL=
TEST_ENV += -e MAIL_MAILER=array
TEST_ENV += -e QUEUE_CONNECTION=sync
TEST_ENV += -e SESSION_DRIVER=array
TEST_ENV += -e PULSE_ENABLED=false
TEST_ENV += -e TELESCOPE_ENABLED=false
TEST_ENV += -e NIGHTWATCH_ENABLED=false

.PHONY: docker-env docker-build docker-up docker-down docker-reset docker-test docker-shell docker-logs

docker-env:
	@if [ ! -f "$(APP_ENV)" ]; then \
		cp "$(DOCKER_ENV)" "$(APP_ENV)"; \
		echo "Created $(APP_ENV) from $(DOCKER_ENV)."; \
	else \
		echo "$(APP_ENV) already exists; keeping current values."; \
	fi

docker-build:
	$(COMPOSE) build app

docker-up: docker-env
	$(COMPOSE) up -d mysql redis mongodb mailpit
	$(COMPOSE) build app
	$(COMPOSE) run --rm app composer install
	$(COMPOSE) run --rm vite npm ci
	$(COMPOSE) run --rm app sh /opt/docker/bootstrap.sh
	$(COMPOSE) up -d app nginx queue vite

docker-down:
	$(COMPOSE) down

docker-reset: docker-env
	$(COMPOSE) up -d mysql redis mongodb
	$(COMPOSE) run --rm app php artisan migrate:fresh --seed --force

docker-test: docker-env
	$(COMPOSE) run --rm $(TEST_ENV) app php artisan test

docker-shell: docker-env
	$(COMPOSE) exec app bash

docker-logs:
	$(COMPOSE) logs -f app nginx queue vite
