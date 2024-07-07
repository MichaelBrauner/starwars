## —— 🎵 The Makefile 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

build: ## Builds the ready-to-run application
	yarn install --force
	yarn build
	composer install
	bin/console cache:clear

quality: ## Run quality checks
	vendor/bin/phpstan analyse
	vendor/bin/psalm
	vendor/bin/ecs --fix

test: ## Run tests
	bin/phpunit --testdox