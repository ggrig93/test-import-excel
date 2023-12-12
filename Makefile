.PHONY: build up migrate queue

build:
	docker-compose build

up:
	docker-compose up -d
	docker-compose exec app composer install --ignore-platform-req=ext-gd

migrate:
	docker-compose exec app php artisan migrate

queue:
	docker-compose exec app php artisan queue:work

all: build up migrate queue
