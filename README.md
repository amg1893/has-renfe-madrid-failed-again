# Has Renfe Madrid failed again?

This application provides you information about the Cercanias Madrid line status based on the tweets.

## Requirements

This application requires a MySQL database. The structure and basic data are provided inside the _sql_ folder.

## Installation

```
composer install
php -S localhost:9000 -t web
```

## Changelog

### 0.0.3

To-Do:
- Create words list to determine the status of the line.

### 0.0.2

- Rebase: Symfony framework.
  - Migrations for the database
  - ORM with Doctrine

### 0.0.1

- Checks tweets from @cercaniasmadrid with the hashtags from database.
- Silex base.