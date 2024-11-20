_default:
    just --list

# Init project
setup-app:
    @just dev-up
    docker compose exec php-fpm composer install
    @just clear-cache
    docker compose exec supervisor bash -c 'supervisorctl stop all'
    docker compose exec supervisor bash -c 'supervisorctl start all'

# Launch docker containers
dev-up:
    docker compose up -d --build --remove-orphans

# Stop and reload docker containers
reload-container:
    -docker compose exec supervisor bash -c 'supervisorctl stop all'
    docker compose stop
    @just dev-up

# Stop docker containers
dev-down:
	docker compose down --remove-orphans

# Suppress and clear cache files
clear-cache ENV="dev":
    docker compose exec php-fpm bash -c 'rm -rf var/cache/{{ENV}}/*'
    docker compose exec php-fpm bash -c "bin/console cache:clear --verbose"

# Fix cs fixer rules
cs-fixer-fix:
	docker compose exec php-fpm bash -c "vendor/bin/php-cs-fixer fix --verbose"

# Verify cs fixer rules
cs-fixer-check:
	docker compose exec php-fpm bash -c "vendor/bin/php-cs-fixer fix --verbose --dry-run"

# Run psalm with cache
psalm:
    docker compose exec php-fpm bash -c "vendor/bin/psalm --find-unused-psalm-suppress"

# Run psalm without cache
psalm-no-cache:
    docker compose exec php-fpm bash -c "vendor/bin/psalm --no-diff --find-unused-psalm-suppress"

# Run tests php
php-tests:
    @just clear-cache test
    docker compose exec php-fpm bash -c "./bin/phpunit --coverage-html=var/cache/coverage tests"

# Run tests on ci
tests:
    docker compose exec php-fpm composer install
    -@just cs-fixer-check
    -@just psalm-no-cache
    @just php-tests

# Connect to backend container
shell-backend:
    docker compose exec php-fpm bash

# Connect to supervisor container
shell-supervisor:
    docker compose exec supervisor bash

# Execute console command for send message with country code given
send-code-country CODE_COUNTRY:
    docker compose exec php-fpm bash -c "bin/console app:capital:send-message {{CODE_COUNTRY}}"
