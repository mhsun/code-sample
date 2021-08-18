## Project Setup

#### setup composer
Go to the project directory and open the terminal and hit

``composer install``

Then add an .env file in the root of the project directory, or you may copy the .env.copy file and rename it to .env

Next you should fill up the **APP_KEY** in the .env file with a long random string, or you hit the command below

``php artisan key:generate``

#### setup database
You need to fill up the database credentials in the .env file. Create a database according to you connection. The following fields need to filled up in the .env file

    DB_CONNECTION= default is mysql
    DB_HOST=  default is 127.0.0.1
    DB_PORT=  default is 3306
    DB_DATABASE=  
    DB_USERNAME=  
    DB_PASSWORD=

After setting up the credentials you need to hit the following command

`php artisan migrate`

A default seeder is added to setup a few data for testing. You may seed them by

`php artisan db:seed`

You're ready to go. Serve the application and visit it at the url provided in the terminal.

`php artisan serve`
