#
# Constants
#
APP_NAME = $(shell basename $(CURDIR))
CACHE_HOME = $(if $(XDG_CACHE_HOME),$(XDG_CACHE_HOME),$(CURDIR)/.cache)/$(APP_NAME)

#
# Variables
#
PHP_VERSION = 8.3
COMPOSER_VERSION = 2.7.9
BUILD_CACHE = $(CACHE_HOME)/$(PHP_VERSION)-$(COMPOSER_VERSION)
UP_CACHE = $(CACHE_HOME)/php-fpm.pid

DOCKER_COMPOSE = docker compose --file compose.yml

.PHONY: build
build: $(BUILD_CACHE)
$(BUILD_CACHE): docker/php/Dockerfile composer.lock
	@mkdir -p $(@D)
	@$(DOCKER_COMPOSE) build \
		--build-arg PHP_VERSION=$(PHP_VERSION) \
		--build-arg COMPOSER_VERSION=$(COMPOSER_VERSION)
	@touch $@

.PHONY: up
up: $(UP_CACHE)
$(UP_CACHE): $(BUILD_CACHE)
	@mkdir -p $(@D)
	@$(DOCKER_COMPOSE) up -d --remove-orphans
	@$(DOCKER_COMPOSE) cp app:/var/run/php-fpm.pid $@

.PHONY: down
down:
	@$(DOCKER_COMPOSE) down --volumes --remove-orphans
	@rm -f $(UP_CACHE)

.PHONY: shell
shell: $(UP_CACHE)
	@$(DOCKER_COMPOSE) exec app bash
