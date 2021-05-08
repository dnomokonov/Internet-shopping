#!/bin/sh

sleep 10

while [[ ! -f vendor/autoload.php ]];
do
      sleep 2
done

php /var/www/bin/console doctrine:migrations:migrate
php-fpm
