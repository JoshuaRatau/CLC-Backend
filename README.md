#CLC LaraveL Backend API SETUP


### Prerequisities

1. PHP >= 8.0
2. Composer
3. MySQL Database
4. XAMPP (includes PHP and MySql)



## Install Composer

1. Download Composer from https://getcomposer.org/download/

2. Follow the installation instructions for your operating system.

3. Confirm the installation by checking the version using this command composer -v



## Download and install XAMPP

1. Download XAMPP from https://www.apachefriends.org/index.html and install it.

2. Open the XAMPP Control Panel and start the Apache and MySQL services.



## Set up a MySQL Database in XAMPP on Your Local Server


1. Open your browser and go to http://localhost/phpmyadmin.

2. In phpMyAdmin, create a new database for your Laravel project (e.g., clc_db).

3.Note the username (root by default), and password (leave blank by default).

## Cloning the repository

1. git clone https://github.com/JoshuaRatau/CLC-Backend/tree/master

2. cd your-laravel-repo

3. composer install

4. open .env in a text editor and update the database esttings to match your XAMPP MySQL credentials:


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db    # Replace with your database name
DB_USERNAME=root          # Default XAMPP MySQL username
DB_PASSWORD=              # Leave blank if no password is set in XAMPP


## Generate the Application key using the following command in the terminal: 

php artisan key:generate

## Run database Migration: 


Run Database Migrations:

## Start the Laravel local development Server:

php artisan serve


## The API should now be running at http://127.0.0.1:8000.





