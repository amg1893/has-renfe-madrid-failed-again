# Has Renfe Madrid failed again?

This application provides you information about the Cercanias Madrid line status based on the tweets.

## Requirements

This application requires a MySQL database. The structure and basic data are provided with migrations in Symfony.

## Installation

```
composer install
yarn install
yarn encore dev
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php -S localhost:9000 -t web
```