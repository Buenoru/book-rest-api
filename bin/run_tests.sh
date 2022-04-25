#!/bin/sh

BASEDIR=$(dirname "$0")

php "$BASEDIR/console" --env=test doctrine:database:create --if-not-exists
php "$BASEDIR/console" --env=test doctrine:schema:drop --force
php "$BASEDIR/console" --env=test doctrine:schema:create
php "$BASEDIR/console" --env=test doctrine:fixtures:load -q
php "$BASEDIR/phpunit"
