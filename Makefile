shell:
	docker compose run --rm php sh

test:
	docker compose run --rm php vendor/bin/phpunit --testdox

qa:
	docker compose run --rm php vendor/bin/phpstan analyze
	docker compose run --rm php vendor/bin/ecs --fix