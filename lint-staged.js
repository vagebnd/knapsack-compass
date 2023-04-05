module.exports = {
  '*.php': (files) => [
    './vendor/bin/phpstan analyse --memory-limit=2G',
    `./vendor/bin/php-cs-fixer fix --config .php-cs-fixer.php ${files.join(' ')}`,
  ],
  'composer.json': () => 'composer validate --no-check-publish --strict',
}
