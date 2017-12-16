#!/bin/bash
sudo chmod -R 777 app/cache/ app/logs/ 
if [ "$1" == "create" ] || [ "$1" == "remove" ]; then
    php app/console doctrine:schema:drop --force && php app/console core:actor $1 && php app/console doctrine:schema:create && php app/console doctrine:fixtures:load --append 
else
    php app/console doctrine:schema:drop --force && php app/console doctrine:schema:create && php app/console doctrine:fixtures:load --append
fi
sudo rm -rf app/cache/* app/logs/*

