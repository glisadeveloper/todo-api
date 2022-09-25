# todo
Laravel API - TO DO list (example by Gligorije)

	* Laravel API example
	* This example shows how one should restful service work
	* Through the developing process, I use Postman for testing endpoints 
	* RestFul Api support method: GET (data display), POST (data entry), PUT (updating data), DELETE (deleting data)

### Following are the Models

    * Todo
    * Task
    * User ( Edited as needed)

### Usage

* Clone the project via git clone or download the zip file.

### .env

* Create a database and connect your database in .env file.
* Add your smtp params ( for email send ) in .env file.

### Composer Install

cd into the project directory via terminal and run the following command to install composer packages.
* composer install

### Run Migration

* then run the following command to create migrations in the databbase.

php artisan migrate

### Run Passport new token

* Each time you do database migration you drop Oauth table as well.

php artisan passport:client --personal

### Run project via composer

php artisan serve

### API EndPoints
Please use Postman collection https://www.getpostman.com/collections/70b2fbadc90d539ed6a6 to test below endpoints ( also replace localhost with your domain or ip address ): 

    * 

### Other information
	* This is a simple example of creating Api from scratch using Laravel framework
	* Api contains tasks ( dayly list - todo ) with email cron at midnight for each user timezone 
