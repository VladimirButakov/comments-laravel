.PHONY: help build up down restart logs shell artisan migrate swagger install setup

help: ## Показать справку
	@echo "Доступные команды:"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Собрать контейнеры
	docker-compose build

up: ## Запустить контейнеры
	docker-compose up -d

down: ## Остановить контейнеры
	docker-compose down

restart: ## Перезапустить контейнеры
	docker-compose restart

logs: ## Показать логи
	docker-compose logs -f

shell: ## Войти в контейнер
	docker-compose exec app bash

artisan: ## Выполнить artisan команду (make artisan CMD="route:list")
	docker-compose exec app php artisan $(CMD)

migrate: ## Запустить миграции
	docker-compose exec app php artisan migrate

swagger: ## Сгенерировать Swagger документацию
	docker-compose exec app php artisan l5-swagger:generate
	docker-compose exec app cp storage/api-docs/api-docs.json public/api-docs.json

install: ## Собрать и запустить проект с нуля
	docker-compose build
	docker-compose up -d
	@echo "Ожидание запуска MySQL..."
	@sleep 10
	docker-compose exec app composer install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate
	docker-compose exec app php artisan l5-swagger:generate
	docker-compose exec app cp storage/api-docs/api-docs.json public/api-docs.json
	@echo "Готово! API: http://localhost:8080/api/v1"
	@echo "Swagger: http://localhost:8080/api/documentation"

setup: ## Настроить уже собранный проект
	docker-compose exec app composer install
	docker-compose exec app php artisan migrate
	docker-compose exec app php artisan l5-swagger:generate
	docker-compose exec app cp storage/api-docs/api-docs.json public/api-docs.json
	@echo "Готово!"
