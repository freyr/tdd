build:
	docker compose build

shell:
	docker compose run --rm php sh

test:
	docker compose run --rm php vendor/bin/phpunit --testdox