const micromatch = require("micromatch");

module.exports = {
  "*.php": (files) => [
    `./vendor/bin/pint --config resources/config/pint.json`,
    "./vendor/bin/phpstan analyse --memory-limit=2G",
  ],
  "composer.json": () => "composer validate --no-check-publish --strict",
};
