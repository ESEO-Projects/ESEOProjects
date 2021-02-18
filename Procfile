release: composer dump-env prod
release: php bin/console secrets:generate-keys
release: php bin/console secrets:generate-keys --env=prod
web: heroku-php-apache2 public/
