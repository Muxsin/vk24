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
    - Description: _A method that returns the history of tasks completed by the user and his balance._
- `POST` /api/users
    - Parameters: `name`
    - Description: _User creation method._

### Quest

- `POST` /api/quests
    - Parameters: `name`, `cost`
    - Description: _Quest creation method._
- `POST` /api/quests/completed
    - Parameters: `user_id`, `quest_id`
    - Description: _A method that signals to the service that some event has occurred, the user has fulfilled the
      condition, and the task can be considered completed and a reward can be given to the user._
