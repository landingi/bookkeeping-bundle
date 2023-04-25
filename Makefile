ci:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M
	vendor/bin/ecs check --config vendor/landingi/php-coding-standards/ecs.php
	vendor/bin/phpunit --testsuite=unit,functional --coverage-clover=var/coverage/coverage.xml
ci-all:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M
	vendor/bin/ecs check --config vendor/landingi/php-coding-standards/ecs.php
	vendor/bin/phpunit --testsuite=all --coverage-clover=var/coverage/coverage.xml
fix:
	vendor/bin/ecs check --fix --config vendor/landingi/php-coding-standards/ecs.php
test:
	vendor/bin/phpunit --color=always --testsuite=all
unit:
	vendor/bin/phpunit --color=always --testsuite=unit
functional:
	vendor/bin/phpunit --color=always --testsuite=functional
integration:
	vendor/bin/phpunit --color=always --testsuite=integration
coverage:
	vendor/bin/phpunit --testsuite=all --coverage-text
coverage-html:
	vendor/bin/phpunit --testsuite=all --coverage-html=var/coverage/
analyse:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M
	vendor/bin/ecs check --config vendor/landingi/php-coding-standards/ecs.php
run:
	composer install --no-interaction --prefer-dist
	exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
