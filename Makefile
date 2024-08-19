.PHONY: install
install:
	composer install

.PHONY: phpcs
phpcs:
	./vendor/bin/phpcs --standard=./phpcs.xml ./src/FondOfKudu/* ./tests/*

.PHONY: phpcbf
phpcbf:
	./vendor/bin/phpcbf --standard=./phpcs.xml ./src/FondOfKudu/* ./tests/*

.PHONY: phpstan
phpstan:
	./vendor/bin/phpstan --memory-limit=-1 analyse ./src/FondOfKudu ./tests

.PHONY: codeception
codeception:
	./vendor/bin/codecept run --env standalone --coverage --coverage-xml --coverage-html

.PHONY: ci
ci: phpcs codeception phpstan

.PHONY: clean
clean:
	rm -Rf composer.lock
	rm -Rf ./vendor
	find ./tests/_output/ -not -name .gitkeep -delete
	rm -Rf src/Generated/*
	rm -Rf src/Orm/*
