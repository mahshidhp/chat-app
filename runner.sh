cd "$(dirname -- "$0")" || return
vendor/propel/propel/bin/propel sql:build
vendor/propel/propel/bin/propel  model:build
vendor/propel/propel/bin/propel  sql:insert

cd public || return
php -S localhost:8888
