#!/bin/bash

chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

cd /var/www/html && composer install

php artisan key:generate

php artisan migrate

php-fpm
