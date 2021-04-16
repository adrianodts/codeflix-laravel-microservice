#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
#cp .env.example .env
#cp .env.testing.example .env.testing
entrypoint: dockerize -template ./.docker/app/.env:.env -template ./.docker/app/.env.testing:.env.testing -wait tcp://db:3306 -timeout 40s ./.docker/entrypoint.sh
#chown -R www-data:www-data .
composer install
php artisan key:generate
php artisan migrate

php-fpm
