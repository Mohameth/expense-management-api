
# Expense management API
This is a small project about an employee needing to handle his expense notes from multiple companies. 

## Structure
There are three entities: User, Company and, ExpenseNote. A User can have multiple ExpenseNote, each ExpenseNote is linked to a company and a User. 

## Details 
Project use Symfony 6.4, MySQL and PHPUnit

# Installation

## Requirements
Install PHP 8.0+

Install symfony CLI here: https://symfony.com/download

Install MySQL (or the database of your choice, database url will need to be adapted)

## Project installation
First clone the repository 
```
git clone https://github.com/Mohameth/expense-management-api.git
```
Then run the installation of the project
```
symfony composer install
 ```
Configure the .env file by changing the DATABASE_URL to connect to your database

Run the server on another command prompt (or as a background task) with
```
symfony server:start
```

## Database 
Create the main database with: 
```
symfony console doctrine:database:create
```
Create the test database with: 
```
symfony console doctrine:database:create --env=test
```
Run the migrations for both environments with:
```
symfony console doctrine:migrations:migrate
symfony console doctrine:migrations:migrate --env=test
```
Populate the database with some fixtures: 
```
symfony console doctrine:fixtures:load
```

The project is running! 

# Usage

The following routes were defined: 
 - GET `/api/expenses` Get all expense notes
 - GET `/api/expenses/{id}` Fetch one expense note with its id 
 - POST `/api/expenses` To create a new expensenote, add in the body of the request something like 
 ```
 {
    "amount": 33.4,
    "type": "essence",
    "date": "2024-02-09",
    "companyId": 2
}
```
 - PUT `/api/expenses/{id}` To update a field of an expense note, use it the same as above post with the desired field.
 - DEL `/api/expenses/{id}` Delete the expense note with the id `{id}`
 - POST `/api/login` Get a token to authenticate
 - GET `/api/protected/test` To test the login, require the token created with the login route

 ## Test 
 In the file tests/Controller/ExpenseNoteControllerTest there is a function to functionally test that Create works correctly:
  - Use `php bin/phpunit` to run all the tests

## Authentication
For the authentication since it's an api I use a system with tokens, I use a JWT dependency underneath to generate a new token. 
