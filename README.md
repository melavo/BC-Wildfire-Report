# **B.C Wildfire Report**

## **Introduction**

This application is for the residents of B.C. to monitor the status of wildfires. The requirements span from frontend design, API handling to deploying the solution in a portable way. 

## **Instructions for Local Deployment Using Docker:**

Docker environment required to run Laravel (based on official php and mysql docker hub repositories).

#### Requirements

· Docker version 18.06 or later

· Docker compose version 1.22 or later

Note: OS recommendation - Linux Ubuntu based.

#### Components

1. Nginx 1.25
2. PHP 8.1 fpm
3. MySQL 8
4. Laravel 10



#### **Steps to Deploying local environment**

1. You can clone this repository from GitHub or install via composer.

Note: Delete storage/mysql-data and vendor folder if it is exists.

If you have installed composer and want to install environment via composer you can use next cmd command:

git clone https://github.com/melavo/BC-Wildfire-Report.git

cd BC-Wildfire-Report

Note: If you want to change default docker configurations (web_port, etc...) - create uncommitted .env file, copy data from .env.dev, edit necessary environment variable value.  You can copy Client ID and Client secrets of GitHub App to the field: GITHUB_CLIENT_ID, GITHUB_CLIENT_SECRET, and Goole Map Public Key to GOOGLE_MAPS_PUBLIC_KEY.

2. Build, start and install the docker images from your terminal:

make build-staging

make start-staging

3. Make sure that you have installed migrations:

make migrate-no-test

4. Set key for application

make key-generate



## CI/CD

To publish the app, I was able to add GitHub Actions workflow that builds and deploys that would automatically build, unit test and deploy the app to Vultr Cloud server. 

## **Unit Testing:**	

Script for the unit test are located in the folder /tests/. To run the test the following command is called: php ./vendor/bin/phpunit.

## **Querying API Request Log:**	

API Request are logged into the MySQL table visitor_logs. 

To query and determine number of API request made run SELECT count(id) FROM  visitor_logs to get the number of API request made.



## **Application Summary**

Using Laravel as the back-end framework, DataTables for grid views in the front-end, Nginx as a web server, Docker for containerization, and MySQL as the database gives us a full-stack approach to our solution.

Here's a high-level solution and implementation strategy:

## **Solution Outline:**

- Front-End: A web interface using Laravel Blade and DataTables to display and filter wildfire data.
- Back-End: Laravel backend to handle business logic, API communications, and data storage/retrieval.
- Database: MySQL database to store user login data and visitor log.
- Deployment: Docker container comprising of an Nginx web server, PHP for Laravel, and MYSQL 

## **Features:**

- Clean and intuitive UI interface with paging and sorting capabilities
- Themes to the BC Gov Style Guide
- Downloadable csv file by all records or filtered results
- Filtering Capabilities
- Coordinate location viewable via embedded map to allowing easy visualize and locate area affected
- Logging capabilities to record API request into database for easy query of log  

## **Implementation Steps:**

- Setup Laravel Project:

- - Initialize a new Laravel project using Composer.
  - Set up Laravel's built-in authentication for bonus user stories.

- Backend (Laravel):

- - Create routes and controllers to handle API endpoints.
  - Communicate with the provided wildfire API using Laravel's HTTP client.

- Frontend (Laravel Blade & DataTables):

- - Use Laravel Blade to create views.
  - Integrate DataTables to display wildfire data in a grid view. DataTables allows instant searching, filtering, and pagination, which will be essential for our use case.
  - Add a 'download' button to enable the user to export data.

- Database (MySQL):

- - Use Laravel migrations to create the required tables.
  - Implement Eloquent models to communicate with the database.

- Dockerization:

- - Create a Dockerfile for the Laravel application.

  - Create a docker-compose.yml that defines services for:

  - - Nginx
    - PHP (FPM)
    - MySQL

  - Ensure Nginx is configured to work with Laravel and PHP-FPM.

- Deployment Scripts:

- - Add necessary scripts or instructions for docker-compose up in the README.

- GitHub OAuth Integration:

- - Use Laravel's Socialite package to integrate with GitHub OAuth.
  - Protect routes/views so they're only accessible to authenticated users.

- Documentation:

- Write detailed steps on setting up the application locally using Docker.
  - Include any prerequisites, installation steps, and how to use the application.

- Version Control:

- Initialize a Git repository.
  - Use GitHub for version control.
  - Commit changes regularly, following best practices.


  ## **Conclusion:**

- With Laravel's robust framework capabilities, DataTables' grid features, Nginx's reliability, MySQL's storage, and Docker's containerization, this solution provides a comprehensive, scalable, and deployable system for B.C.'s residents to monitor wildfires.
