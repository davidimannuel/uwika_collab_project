run-web:
	php artisan serve

run-migration-fresh:
	php artisan migrate:fresh --seed

run-migration:
	php artisan migrate:fresh

init-docker-folders:
	mkdir -p ./docker_volumes/postgre