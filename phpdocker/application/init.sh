#!/bin/sh

cd /application
composer install && bin/console d:m:m --no-interaction

php-fpm7.3