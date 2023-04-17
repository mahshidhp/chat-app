cd "$(dirname -- "$0")" || return
vendor/propel/propel/bin/propel sql:build
vendor/propel/propel/bin/propel model:build
vendor/propel/propel/bin/propel config:convert
vendor/propel/propel/bin/propel sql:insert

composer dump-autoload

php -S localhost:8888
