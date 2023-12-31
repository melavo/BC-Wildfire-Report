export COMPOSE_PROJECT_NAME=environment3
export WEB_PORT_HTTP=80
export WEB_PORT_SSL=443
export XDEBUG_CONFIG=main
export INNODB_USE_NATIVE_AIO=1
export SQL_MODE=ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION

# Determine if .env file exist
ifneq ("$(wildcard .env)","")
	include .env
endif

ifndef INSIDE_DOCKER_CONTAINER
	INSIDE_DOCKER_CONTAINER = 0
endif

HOST_UID := $(shell id -u)
HOST_GID := $(shell id -g)
PHP_USER := -u www-data
PROJECT_NAME := -p ${COMPOSE_PROJECT_NAME}
INTERACTIVE := $(shell [ -t 0 ] && echo 1)
ERROR_ONLY_FOR_HOST = @printf "\033[33mThis command for host machine\033[39m\n"
.DEFAULT_GOAL := help
ifneq ($(INTERACTIVE), 1)
	OPTION_T := -T
endif
ifeq ($(GITLAB_CI), 1)
	# Determine additional params for phpunit in order to generate coverage badge on GitLabCI side
	PHPUNIT_OPTIONS := --coverage-text --colors=never
endif

help: ## Shows available commands with description
	@echo "\033[34mList of available commands:\033[39m"
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-27s[0m %s\n", $$1, $$2}'

build: ## Build dev environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose.yml build
else
	$(ERROR_ONLY_FOR_HOST)
endif

build-test: ## Build test or continuous integration environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-test-ci.yml build
else
	$(ERROR_ONLY_FOR_HOST)
endif

build-staging: ## Build staging environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-staging.yml build
else
	$(ERROR_ONLY_FOR_HOST)
endif

build-prod: ## Build prod environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-prod.yml build
else
	$(ERROR_ONLY_FOR_HOST)
endif

start: ## Start dev environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose.yml $(PROJECT_NAME) up -d
else
	$(ERROR_ONLY_FOR_HOST)
endif

start-test: ## Start test or continuous integration environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-test-ci.yml $(PROJECT_NAME) up -d
else
	$(ERROR_ONLY_FOR_HOST)
endif

start-staging: ## Start staging environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-staging.yml $(PROJECT_NAME) up -d
else
	$(ERROR_ONLY_FOR_HOST)
endif

start-prod: ## Start prod environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-prod.yml $(PROJECT_NAME) up -d
else
	$(ERROR_ONLY_FOR_HOST)
endif

stop: ## Stop dev environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose.yml $(PROJECT_NAME) down
else
	$(ERROR_ONLY_FOR_HOST)
endif

stop-test: ## Stop test or continuous integration environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-test-ci.yml $(PROJECT_NAME) down
else
	$(ERROR_ONLY_FOR_HOST)
endif

stop-staging: ## Stop staging environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-staging.yml $(PROJECT_NAME) down
else
	$(ERROR_ONLY_FOR_HOST)
endif

stop-prod: ## Stop prod environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose -f docker-compose-prod.yml $(PROJECT_NAME) down
else
	$(ERROR_ONLY_FOR_HOST)
endif

restart: stop start ## Stop and start dev environment
restart-test: stop-test start-test ## Stop and start test or continuous integration environment
restart-staging: stop-staging start-staging ## Stop and start staging environment
restart-prod: stop-prod start-prod ## Stop and start prod environment

env-dev: ## Creates config for dev environment
	@make exec cmd="cp ./.env.dev ./.env"

env-test-ci: ## Creates config for test/ci environment
	@make exec cmd="cp ./.env.test-ci ./.env"

ssh: ## Get bash inside laravel docker container
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) laravel bash
else
	$(ERROR_ONLY_FOR_HOST)
endif

ssh-root: ## Get bash as root user inside laravel docker container
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec $(OPTION_T) laravel bash
else
	$(ERROR_ONLY_FOR_HOST)
endif

ssh-nginx: ## Get bash inside nginx docker container
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec nginx /bin/sh
else
	$(ERROR_ONLY_FOR_HOST)
endif

ssh-supervisord: ## Get bash inside supervisord docker container (cron jobs running there, etc...)
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec supervisord bash
else
	$(ERROR_ONLY_FOR_HOST)
endif

ssh-mysql: ## Get bash inside mysql docker container
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec mysql bash
else
	$(ERROR_ONLY_FOR_HOST)
endif

exec:
ifeq ($(INSIDE_DOCKER_CONTAINER), 1)
	@$$cmd
else
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) laravel $$cmd
endif

exec-bash:
ifeq ($(INSIDE_DOCKER_CONTAINER), 1)
	@bash -c "$(cmd)"
else
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) laravel bash -c "$(cmd)"
endif

exec-by-root:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) SQL_MODE=$(SQL_MODE) docker-compose $(PROJECT_NAME) exec $(OPTION_T) laravel $$cmd
else
	$(ERROR_ONLY_FOR_HOST)
endif

report-prepare:
	@make exec cmd="mkdir -p reports/coverage"

report-clean:
	@make exec-by-root cmd="rm -rf reports/*"

wait-for-db:
	@make exec cmd="php artisan db:wait"

composer-install-no-dev: ## Installs composer no-dev dependencies
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader --no-dev"

composer-install: ## Installs composer dependencies
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader"

composer-update: ## Updates composer dependencies
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer update"

key-generate: ## Sets the application key
	@make exec cmd="php artisan key:generate"

info: ## Shows Php and Laravel version
	@make exec cmd="php artisan --version"
	@make exec cmd="php artisan env"
	@make exec cmd="php --version"

logs: ## Shows logs from the laravel container. Use ctrl+c in order to exit
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-laravel
else
	$(ERROR_ONLY_FOR_HOST)
endif

logs-nginx: ## Shows logs from the nginx container. Use ctrl+c in order to exit
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-nginx
else
	$(ERROR_ONLY_FOR_HOST)
endif

logs-supervisord: ## Shows logs from the supervisord container. Use ctrl+c in order to exit
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-supervisord
else
	$(ERROR_ONLY_FOR_HOST)
endif

logs-mysql: ## Shows logs from the mysql container. Use ctrl+c in order to exit
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-mysql
else
	$(ERROR_ONLY_FOR_HOST)
endif

drop-migrate: ## Drops databases and runs all migrations for the main/test databases
	@make exec cmd="php artisan migrate:fresh"
	@make exec cmd="php artisan migrate:fresh --env=test"

migrate-no-test: ## Runs all migrations for main database
	@make exec cmd="php artisan migrate --force"

migrate: ## Runs all migrations for main/test databases
	@make exec cmd="php artisan migrate --force"
	@make exec cmd="php artisan migrate --force --env=test"

seed: ## Runs all seeds for test database
	@make exec cmd="php artisan db:seed --force"

phpunit: ## Runs PhpUnit tests
	@make exec cmd="./vendor/bin/phpunit -c phpunit.xml --coverage-html reports/coverage $(PHPUNIT_OPTIONS) --coverage-clover reports/clover.xml --log-junit reports/junit.xml"


composer-normalize: ## Normalizes composer.json file content
	@make exec cmd="composer normalize"

composer-validate: ## Validates composer.json file content
	@make exec cmd="composer validate --no-check-version"

composer-require-checker: ## Checks the defined dependencies against your code
	@make exec-bash cmd="XDEBUG_MODE=off php ./vendor/bin/composer-require-checker"

composer-unused: ## Shows unused packages by scanning and comparing package namespaces against your code
	@make exec-bash cmd="XDEBUG_MODE=off php ./vendor/bin/composer-unused"
