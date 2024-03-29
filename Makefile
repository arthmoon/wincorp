init: init-ci
init-ci: docker-down-clear \
	api-clear \
	docker-pull docker-build docker-up \
	api-init
up: docker-up
down: docker-down
restart: down up
check: lint analyze validate-schema test test-e2e
lint: api-lint
analyze: api-analyze
validate-schema: api-validate-schema
test: api-test api-fixtures
test-unit: api-test-unit
test-functional: api-test-functional api-fixtures
test-smoke: api-fixtures
test-e2e: api-fixtures

update-deps: api-composer-update restart

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

api-init: api-permissions api-composer-install api-wait-db api-migrations api-fixtures rates

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log var/test

api-composer-install:
	docker compose run --rm api-php-cli composer install

api-composer-update:
	docker compose run --rm api-php-cli composer update

api-wait-db:
	docker compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-migrations:
	docker compose run --rm api-php-cli composer app migrations:migrate -- --no-interaction

api-fixtures:
	docker compose run --rm api-php-cli composer app fixtures:load

api-backup:
	docker compose run --rm api-postgres-backup

api-check: api-validate-schema api-lint api-analyze api-test

api-validate-schema:
	docker compose run --rm api-php-cli composer app orm:validate-schema

api-lint:
	docker compose run --rm api-php-cli composer lint
	docker compose run --rm api-php-cli composer php-cs-fixer fix -- --dry-run --diff

api-cs-fix:
	docker compose run --rm api-php-cli composer php-cs-fixer fix

api-analyze:
	docker compose run --rm api-php-cli composer psalm -- --no-diff

api-analyze-diff:
	docker compose run --rm api-php-cli composer psalm

api-test:
	docker compose run --rm api-php-cli composer test

api-test-coverage:
	docker compose run --rm api-php-cli composer test-coverage

api-test-unit:
	docker compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

rates:
	docker exec wincorp-api-php-fpm /usr/local/bin/rates