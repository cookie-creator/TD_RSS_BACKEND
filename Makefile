# vim:ft=entity
# Makefile
include .env
export

up: ## run backend
	docker-compose up -d

install-php:
	 docker-compose -f docker-compose.yml run --rm -it --user root php-worker composer update


build:
	docker-compose build --no-cache --force-rm
laravel-install:
	docker-compose exec backend composer create-project --prefer-dist laravel/laravel .
create-project:
	@entity build
	@entity up
	@entity laravel-install
	docker-compose exec backend php artisan key:generate
	docker-compose exec backend php artisan storage:link
	docker-compose exec backend chmod -R 777 storage bootstrap/cache
	@entity fresh
install-recommend-packages:
	docker-compose exec backend composer require doctrine/dbal
	docker-compose exec backend composer require --dev ucan-lab/laravel-dacapo
	docker-compose exec backend composer require --dev barryvdh/laravel-ide-helper
	docker-compose exec backend composer require --dev beyondcode/laravel-dump-server
	docker-compose exec backend composer require --dev barryvdh/laravel-debugbar
	docker-compose exec backend composer require --dev roave/security-advisories:dev-master
	docker-compose exec backend php artisan vendor:publish --provider="BeyondCode\DumpServer\DumpServerServiceProvider"
	docker-compose exec backend php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
init:
	docker-compose up -d --build
	docker-compose exec backend composer install
	docker-compose exec backend cp .env.example .env
	docker-compose exec backend php artisan key:generate
	docker-compose exec backend php artisan storage:link
	docker-compose exec backend chmod -R 777 storage bootstrap/cache
	@entity fresh
remake:
	@entity destroy
	@entity init
stop:
	docker-compose stop
	docker-compose -f docker-compose.local.tests.yml stop
down:
	docker-compose down --remove-orphans
restart:
	@entity down
	@entity up
destroy:
	docker-compose down --rmi all --volumes --remove-orphans
destroy-volumes:
	docker-compose down --volumes --remove-orphans
ps:
	docker-compose ps
logs:
	docker-compose logs
logs-watch:
	docker-compose logs --follow
log-web:
	docker-compose logs web
log-web-watch:
	docker-compose logs --follow web
log-backend:
	docker-compose logs backend
log-backend-watch:
	docker-compose logs --follow backend
log-db:
	docker-compose logs db
log-db-watch:
	docker-compose logs --follow db
backend:
	docker-compose exec --user www-data backend bash
backend-root:
	docker-compose exec --user root backend bash
migrate:
	docker-compose exec backend php artisan migrate
fresh:
	docker-compose exec backend php artisan migrate:fresh --seed
seed:
	docker-compose exec backend php artisan db:seed
dacapo:
	docker-compose exec backend php artisan dacapo
rollback-test:
	docker-compose exec backend php artisan migrate:fresh
	docker-compose exec backend php artisan migrate:refresh
tinker:
	docker-compose exec backend php artisan tinker
test-up:
	docker-compose -f docker-compose.local.tests.yml up -d
test-all:
	docker-compose exec backend-test-runner php artisan test
optimize:
	docker-compose exec backend php artisan optimize
optimize-clear:
	docker-compose exec backend php artisan optimize:clear
cache:
	docker-compose exec backend composer dump-autoload -o
	@entity optimize
	docker-compose exec backend php artisan event:cache
	docker-compose exec backend php artisan view:cache
cache-clear:
	docker-compose exec backend composer clear-cache
	@entity optimize-clear
	docker-compose exec backend php artisan event:clear
npm:
	@entity npm-install
npm-install:
	docker-compose exec web npm install
npm-dev:
	docker-compose exec web npm run dev
npm-watch:
	docker-compose exec web npm run watch
npm-watch-poll:
	docker-compose exec web npm run watch-poll
npm-hot:
	docker-compose exec web npm run hot
yarn:
	docker-compose exec web yarn
yarn-install:
	@entity yarn
yarn-dev:
	docker-compose exec web yarn dev
yarn-watch:
	docker-compose exec web yarn watch
yarn-watch-poll:
	docker-compose exec web yarn watch-poll
yarn-hot:
	docker-compose exec web yarn hot
db:
	docker-compose exec mysql bash
sql:
	docker-compose exec mysql bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
redis:
	docker-compose exec redis redis-cli
ide-helper:
	docker-compose exec backend php artisan clear-compiled
	docker-compose exec backend php artisan ide-helper:generate
	docker-compose exec backend php artisan ide-helper:meta
	docker-compose exec backend php artisan ide-helper:models --nowrite
reset-rights:
	chown -R $$LOCAL_USER_NAME:$$LOCAL_USER_NAME ./
	chmod -R 777 storage bootstrap/cache
