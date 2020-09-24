build:$(.)
	docker build --tag phprom-client .

tests: build
	docker run phprom-client vendor/bin/phpunit
