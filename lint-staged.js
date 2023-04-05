module.exports = {
  "*.php": (files) => ["./vendor/bin/phpstan analyse --memory-limit=2G"],
  "composer.json": () => "composer validate --no-check-publish --strict",
};
