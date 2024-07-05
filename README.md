# PETSHOP APP

## Setup Instructions

### With Docker

- Run `cp .env.example .env`. This should set up the environment variables.
- Run `docker compose up`. This should bring up the app and its other services (Redis and MySQL).
- Run ```docker exec -it petshop_app bash``` to enter into the docker container for the laravel app. And then run `composer install`.
- To run artisan commands, you can exec into the container first and then run the normal `php artisan` commands.
- Run `php artisan migrate`. This should run the migration to setup the database.
- Run `php artisan key:generate`. To Setup application key in the environment variables.
- Run `php artisan db:seed`. This will seed the required user data into your database.

### Without docker
- To run this project without docker, you need to ensure you meet the following requirements.
    - PHP 8.3 installed
    - MySQL ^8.0 database
    - Composer installed
    - Redis installed

Then follow the instructions

- Run `cp .env.example .env`. This should set up the environment variables.
- Run `composer install`.
- Set up your database with the db credentials in the .env file.
- Run `php artisan migrate`. This should run the migration to setup the database. Notice that we are using the alias defined in the previous step.
- Run `php artisan key:generate`. To Setup application key in the environment variables.
- Run `php artisan db:seed`. This will seed the required user data into your database.


### API Documentation
All API endpoints are documented, and can be found by going to `{{base_url}}/api/documentation#/` in the browser.


### Running Tests
You can run `php artisan test`. To run all tests.


### Running Task Scheduler Locally
This project has a scheduled database seeder that runs every midnight at UTC time.
To run the task scheduler locally you can use the command `php artisan schedule:work`. This executes tasks every minute. Or  `php artisan schedule:run`, this will execute any pending tasks and exit.
