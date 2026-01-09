# PHP_Laravel12_Boilerplate

---


## Project Description

PHP_Laravel12_Boilerplate is a Laravel 12 based admin boilerplate project built using the sebastienheyd/boilerplate package.
This project provides a ready-made admin panel with authentication, user management, role & permission system, and AdminLTE 3 UI.

The main goal of this project is to save development time by using a pre-built backend structure instead of creating everything from scratch.


## This boilerplate is suitable for:

Admin panels

CMS systems

ERP / CRM backends

Role-based applications



## Key Features:

Laravel 12 framework

AdminLTE 3 dashboard UI

User Registration & Login

Role & Permission Management

User CRUD (Create, Read, Update, Delete)

Profile Management

Secure Authentication

Sidebar Menu System

Blade Template Engine

MySQL Database Support


## Technologies Used

PHP 8+

Laravel 12

MySQL

Blade Template Engine

AdminLTE 3




---



# Project SetUp

---

## STEP 1: Create Laravel 12 Project

### Command:

```
composer create-project laravel/laravel PHP_Laravel12_Boilerplate "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Boilerplate

```

This will create a fresh Laravel project in a folder called PHP_Laravel12_Boilerplate.



## Step 2 — Install the Boilerplate Package

### Install the AdminLTE 3 Boilerplate package via composer:

```
composer require sebastienheyd/boilerplate

```

This downloads and installs the backend admin boilerplate package into your Laravel app.




## Step 3 — Publish Boilerplate Files

### Publish assets, configuration files, views and other essentials:

```
php artisan vendor:publish --tag=boilerplate

php artisan vendor:publish --tag=boilerplate-views

```

This will copy the boilerplate’s config files to config/boilerplate, language files, public assets (CSS/JS), views, and routes into your Laravel application so you can customize them.



## Step 4 — Configure Database

### Open your .env file and update your database configuration:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_boilerplate
DB_USERNAME=root
DB_PASSWORD=

```

### Create database:

```
laravel12_boilerplate

```


## Step 5 — Run the Migrations

### Now apply the database schema:

```
php artisan migrate

```

This will create all required tables for users, roles, permissions, logs, etc.




## Step 6 — Serve Your App

### Run the built-in Laravel server to launch the project:

```
php artisan serve

```

### Then open in the browser:

```
http://localhost:8000/admin

```

## So you can see this type Output:

### Register Page:


<img width="1912" height="918" alt="Screenshot 2026-01-09 102236" src="https://github.com/user-attachments/assets/e6ce4897-9767-429f-a7e6-391827710d39" />


### Login Page:


<img width="1917" height="942" alt="Screenshot 2026-01-09 102708" src="https://github.com/user-attachments/assets/3a07f6c9-2450-43a1-89c0-7be2ce242cc3" />


### Admin Page(after register/login):


<img width="1914" height="952" alt="Screenshot 2026-01-09 102430" src="https://github.com/user-attachments/assets/4a21ddfa-538b-4103-8f04-04d0ddd6425e" />


### User Profile Page:


<img width="1919" height="951" alt="Screenshot 2026-01-09 102512" src="https://github.com/user-attachments/assets/0c244346-f25e-4788-b34b-475945009966" />


### User Create Page:


<img width="1919" height="950" alt="Screenshot 2026-01-09 102521" src="https://github.com/user-attachments/assets/5558962f-3e92-41e5-a755-bd6d0fa1bbe0" />


### User Page(List):


<img width="1912" height="947" alt="Screenshot 2026-01-09 102534" src="https://github.com/user-attachments/assets/4317d4c0-081b-412e-b103-3ce18f1bf0a8" />


### Role Page(User RolesList):


<img width="1919" height="943" alt="Screenshot 2026-01-09 102549" src="https://github.com/user-attachments/assets/6422299b-f6f2-43d7-ba24-5473d8c9fde0" />


### Logs Page(logs information):


<img width="1915" height="948" alt="Screenshot 2026-01-09 110919" src="https://github.com/user-attachments/assets/792e286f-5653-471b-9f13-dc00ae8abc11" />


### Logout Page:


<img width="1917" height="931" alt="Screenshot 2026-01-09 102629" src="https://github.com/user-attachments/assets/4c78bd96-8809-481c-8e56-8d53b305d936" />




---



# Project Folder Structure:

```
PHP_Laravel12_Boilerplate/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Application controllers
│   │   └── Middleware/         # Auth & role middleware
│   │
│   ├── Models/                 # Eloquent models (User, Role, etc.)
│   └── Providers/
│
├── config/
│   ├── boilerplate/            # Boilerplate configuration files
│   └── app.php
│
├── database/
│   ├── migrations/             # Database tables (users, roles, permissions)
│   └── seeders/
│
├── public/
│   └── assets/
│       └── vendor/boilerplate/ # AdminLTE CSS & JS files
│
├── resources/
│   ├── views/
│   │   └── vendor/
│   │       └── boilerplate/    # Blade views (login, dashboard, users, roles)
│   │
│   └── lang/
│       └── vendor/boilerplate/ # Language files
│
├── routes/
│   ├── web.php                 # Default Laravel routes
│   ├── boilerplate.php         # Admin panel routes
│   └── api.php
│
├── storage/
│
├── .env                        # Environment configuration
├── composer.json
├── artisan
└── README.md

```

