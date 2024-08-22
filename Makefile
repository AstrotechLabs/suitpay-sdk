#!/bin/bash
include .env
SHELL := /bin/bash # Use bash syntax

export TZ=America/Sao_Paulo

export

.PHONY: help
.DEFAULT_GOAL := up

##@ Docker
up: ## Start all project containers
	@echo -e "\n~~> Starting up containers for ${COMPOSE_PROJECT_NAME}..."
	@docker-compose up -d
	@echo -e "~> Access Application through url: http://localhost:${DOCKER_APP_PORT} or https://localhost:${DOCKER_APP_PORT_SSL}"

up-mac: ## Start all project containers (for Mac)
	@echo -e "\n~~> Starting up containers for ${COMPOSE_PROJECT_NAME} (Mac)..."
	@docker-compose -f docker-compose.yml -f docker-compose.mac.yml up -d --build
	@echo -e "~> Access Application through url: http://localhost:${DOCKER_APP_PORT} or https://localhost:${DOCKER_APP_PORT_SSL}"

stop: ## Stop all project containers
	@echo -e "\n~~> Stoping all containers for ${COMPOSE_PROJECT_NAME}..."
	@docker-compose stop
	@docker-compose rm -f
	@echo -e "done!\n"

restart: ## Stop, Remove and Start app containers
	@echo -e "\n~~> Restarting all containers for ${COMPOSE_PROJECT_NAME}..."
	@make stop
	@make up

in: ## Enter in backend app container
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" bash

in-db: ## Enter in app container
	@docker exec -it "${COMPOSE_PROJECT_NAME}-db" bash

logs: ## Show application logs as tail
	@docker-compose logs -f app

nginx-restart: ## Restart nginx service
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" nginx -s reload

nginx-logs: ## Show application nginx logs as tail
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" tail -f /var/log/nginx/application-error.log

##@ Composer

install: ## Composer install dependencies
	@echo -e "~~> Installing composer dependencies..."
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer install -o
	@echo -e "done!\n"

require: ## Run the composer require. (e.g make require PACKAGE="vendor/package")
	@echo -e "~~> Installing ${PACKAGE} Composer package..."
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer require -o "${PACKAGE}"
	@echo -e "done!\n"

require-dev: ## Run the composer require with dev dependency flag. (e.g make require-dev PACKAGE="vendor/package")
	@echo -e "~~> Installing ${PACKAGE} Composer Development package..."
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer require --dev -o "${PACKAGE}"
	@echo -e "done!\n"

update: ## Run the composer update. (e.g make update)
	@echo -e "~~> Updating Composer packages..."
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer update -o
	@echo -e "done!\n"

dump: ## Run the composer dump
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer dump-autoload -o

##@ Quality Tools
cs: ## Run Code Sniffer Tool
	@echo -e "~~> Running PHP Code Sniffer Tool..."
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer run phpcs
	@echo -e "done!\n"

fixer: ## Run PHP Code Beautifier Tool
	@echo -e "~~> Running PHP Code Beautifier Tool..."
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer run phpcbf
	@echo -e "done!\n"

##@ PHP Unit - Tests

test: ## Run the all suite test
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer run test

test-filter: ## Run a single test by method name (e.g make test-filter NAME="testYourTestName")
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer run test:filter ${NAME}

test-unit: ## Run the application unit tests only
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer run test:unit

test-integration: ## Run the application integration tests only
	@docker exec -it "${COMPOSE_PROJECT_NAME}-app" composer run test:integration

test-coverage: ## Run the all suite test and generate the Code Coverage
	@docker exec -it -e XDEBUG_MODE=coverage "${COMPOSE_PROJECT_NAME}-app" composer run test:coverage

test-coverage-ci: ## Run the all suite test and generate the Code Coverage
	@docker exec -i -e XDEBUG_MODE=coverage "${COMPOSE_PROJECT_NAME}-app" composer run test:coverage-ci

##@ Database

db-backup: ## Backup database
	@docker exec "${COMPOSE_PROJECT_NAME}-db" /usr/bin/mysqldump -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" > dump/database-dump.sql

db-restore: ## Restore database
	@cat .docker/dumps/backup.sql | docker exec -i "${COMPOSE_PROJECT_NAME}-db" /usr/bin/mysql -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}"

help:  ## Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
