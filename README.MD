# VK 2024

## Requirement

- PHP 8.2
- MySql DBSM
- Composer

## Installation

1. Run `composer install`
2. Run `php ./marusia migrate`

## Running

1. Run `php -S localhost:8080 -t public`
2. Visit http://localhost:8080
3. Enjoy!

## Extra

- You can run `php ./marusia empty <tablename>` to truncate table.

## Methods

### User

- `GET` /api/users?user_id=1
- `POST` /api/users
    - Parameters: `name`

### Quest

- `POST` /api/quests
    - Parameters: `name`, `cost`
- `POST` /api/quests/completed
    - Parameters: `user_id`, `quest_id`
