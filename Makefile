# Executables (local)
DOCKER_COMP = docker-compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php
NODE_CONT = $(DOCKER_COMP) exec node

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console
YARN     = $(NODE_CONT) yarn

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images (dev mode)
	@$(DOCKER_COMP) build -f docker-compose.yml -f docker-compose.debug.yml --pull --no-cache

up: ## Start the docker hub in detached mode (no logs, dev mode)
	@$(DOCKER_COMP) -f docker-compose.yml -f docker-compose.debug.yml -f docker-compose.override.yml up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

node: ## Connect to the node container
	@$(NODE_CONT) sh

watch: ## Watch for changes and build assets for dev
	@$(YARN) watch

build-assets: ## Build assets for prod
	@$(YARN) build

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf
