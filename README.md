# todo
Laravel API - TO DO list (example by Gligorije)

	* Laravel API example
	* This example shows how one should restful service work
	* Through the developing process it was use Postman for testing endpoints 
	* Api support method: GET (data display), POST (data entry), PUT (updating data), DELETE (deleting data)

### Following are the Models

    * Todo
    * Task
    * User ( Edited as needed)

### Usage

* Clone the project via git clone or download the zip file.

### .env

* Create a database and connect your database in .env file.
* Add your smtp params ( for email send ) in .env file.

### Run Migration

* then run the following command to create migrations in the database.

php artisan migrate

### Run User Seeder

Add defined list of users ( /database/seeders/UserSeeder.php ).
* php artisan db:seed --class=UserSeeder

### Run Passport new token

* Each time you do database migration you drop Oauth table as well.

php artisan passport:client --personal

### Run project via composer

php artisan serve

### API EndPoints
Please use Postman collection https://www.getpostman.com/collections/70b2fbadc90d539ed6a6 to test below endpoints ( also replace localhost with your domain or ip address ): 

    * Login 
     	- You can log in to the todo-api and using the Bearer token you can use for other endpoints ( required )
    * Register
     	- You can register using this endpoint with unique email address
    * Logout
    	- Delete token ( revoke ) which are linked to the user 	
    * GET User informations
        - Fetch all information about the logged-in user
    * Edit User informations
    	- Update user informations
    * Edit User informations - timezone
    	- Change/update user timezone ( on the basis of which the email is sent )
    * Delete User by ID
    	- Each user can delete only his data (User, Todo, Task (relation))	
    * ADD TODO list
    	- Add daily lists
    * Edit TODO list by ID 
    	- Edit daily list using id ( api/todo/update/{todo_id})	
    * GET all TODO lists
    	- Fetch all daily list for the logged-in user	
    * GET TODO lists by date
    	- Fetch all daily list using filter date
    * GET TODO lists by title
    	- Fetch all daily list using filter title
    * GET TODO list by ID
    	- Fetch single daily list
    * Delete TODO list by ID
    	- Each user can delete only his todo list
    * ADD Task
    	- Creating task that will be used later for schedule	 
    * Complete Task
    	- Mark task as done ( false it is the default )
    * GET all Tasks
    	- Fetch all task belong to a list for the logged-in user	
    * GET all Tasks by filter done ( completed )
    	- Fetch all task belong to a list for the logged-in user using filter	 
    * GET all Tasks by filter done ( not completed )
    	- Fetch all task belong to a list for the logged-in user using filter	 
    * GET all Tasks by filter deadline	
    	- Fetch all task belong to a list for the logged-in user using filter									    	

### How to test Laravel Task Schedule on Windows
* If you run this example on Windows and want to test the Task Schedule how is work please find on https://www.jdsoftvera.com/how-to-add-laravel-task-schedule-on-windows/ instructions on how to run it

### Other information
	* This is a simple example of creating Api from scratch using Laravel framework
	* OAuth2 server implementation for this Laravel api example ( Laravel Passport ) 
	* Api contains tasks ( dayly list - todo ) with email cron at midnight for each user timezone 
