#!/usr/bin/env bash

set -o nounset

: ${CSFIX_PHP_ARGS:="-vv --diff"}
php-cs-fixer fix . $CSFIX_PHP_ARGS --config-file "./.php_cs"
