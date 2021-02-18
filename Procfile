release: composer dump-env --env=prod
release: php bin/console secrets:decrypt-to-local --force --env=prod
web: heroku-php-apache2 public/
