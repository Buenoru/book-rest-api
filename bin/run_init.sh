#!/bin/sh

BASEDIR=$(dirname "$0")

composer install
php "$BASEDIR/console" doctrine:database:create --if-not-exists
php "$BASEDIR/console" doctrine:schema:create 2>/dev/null # --quiet и --no-interaction не подавляют ошибки при существующей схеме
php "$BASEDIR/console" doctrine:fixtures:load --no-interaction
