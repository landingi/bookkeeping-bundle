ci:
	bin/phpunit
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M
	vendor/bin/ecs check --config vendor/landingi/php-coding-standards/ecs.php
fix:
	vendor/bin/ecs check --fix --config vendor/landingi/php-coding-standards/ecs.php
test:
	bin/phpunit --color=always
coverage:
	bin/phpunit --coverage-text
coverage-html:
	bin/phpunit --coverage-html=build/coverage/
analyse:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M
	vendor/bin/ecs check --config vendor/landingi/php-coding-standards/ecs.php
run:
	composer install --no-interaction --prefer-dist
	exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
