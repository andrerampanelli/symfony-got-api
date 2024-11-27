# Variables
DOCKER = docker
DOCKER_COMPOSE = docker compose
EXEC = $(DOCKER_COMPOSE) exec -it php-fpm
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer
NPM = $(EXEC) npm
SYMFONY_CONSOLE = $(PHP) bin/console

# Colors
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

cache-clear: ## Clear cache
	$(SYMFONY_CONSOLE) cache:clear

first-start: ## First start
	cp .env.example .env
	cp /app/.env.example /app/.env
	$(MAKE) build-start
	$(MAKE) composer-install
	$(MAKE) database-init

## —— ✅ Test —————————————————————————————————————————————————————————————————
.PHONY: tests
tests: ## Run all tests
	$(MAKE) database-init-test
	$(PHP) bin/phpunit

coverage: ## Run all tests with coverage
	$(MAKE) database-init-test
	$(PHP) bin/phpunit --coverage-clover=coverage.xml tests/

lint: ## Run all linters
	$(COMPOSER) run lint 

database-init-test: ## Init database for test
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists --env=test
	$(SYMFONY_CONSOLE) d:d:c --env=test
	$(SYMFONY_CONSOLE) d:m:m --no-interaction --env=test
	$(SYMFONY_CONSOLE) d:f:l --no-interaction --env=test

## —— 🐳 Docker —————————————————————————————————————————————————————————————————
build: ## Build app with fresh images
	$(DOCKER_COMPOSE) build

start: ## Start app
	$(MAKE) docker-start

build-start: ## Build and start app
	$(MAKE) build
	$(MAKE) docker-start

docker-start: ## Start app
	$(DOCKER_COMPOSE) up -d

stop: ## Stop app
	$(MAKE) docker-stop
	
docker-stop: ## Stop app
	$(DOCKER_COMPOSE) stop
	@$(call RED,"The containers are now stopped.")

php-bash: ## Enter in php container
	$(EXEC) bash

## —— 🎻 Composer —————————————————————————————————————————————————————————————————
composer-install: ## Install dependencies
	$(COMPOSER) install

composer-update: ## Update dependencies
	$(COMPOSER) update

composer-clear-cache: ## clear-cache dependencies
	$(COMPOSER) clear-cache

## —— 📊 Database —————————————————————————————————————————————————————————————————
database-init: ## Init database
	$(MAKE) database-drop
	$(MAKE) database-create
	$(MAKE) database-migrate
	$(MAKE) database-fixtures-load

database-drop: ## Drop database
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists

database-create: ## Create database
	$(SYMFONY_CONSOLE) d:d:c --if-not-exists

database-remove: ## Drop database
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists

database-migration: ## Make migration
	$(SYMFONY_CONSOLE) make:migration

migration: ## Alias : database-migration
	$(MAKE) database-migration

database-migrate: ## Migrate migrations
	$(SYMFONY_CONSOLE) d:m:m --no-interaction

migrate: ## Alias : database-migrate
	$(MAKE) database-migrate

database-fixtures-load: ## Load fixtures
	$(SYMFONY_CONSOLE) d:f:l --no-interaction

fixtures: ## Alias : database-fixtures-load
	$(MAKE) database-fixtures-load

## —— 🛠️  Others —————————————————————————————————————————————————————————————————
help: ## List of commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'