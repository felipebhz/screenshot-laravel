## About Screenshot App

**This guide will show how to setup the Backend in Laravel.**


This is an application to provide updated screenshots of the best casinos websites.

With this application you will have access to the reviews and the screenshots of the most common casinos websites in the world.

Some of them are:
- [Betboo](https://www.betboo.com)
- [Bet365](https://www.bet365.com)
- [Awesome Casino](https://www.awesomecasino.com)
- [Betfair](https://www.betfair.com)

## How to run this application
- Prerequisites:
    - Docker Installed on the Host Machine

Full Application is made up of 2 independent parts.
Backend (Laravel) **this application**
and Frontend (React) another application found here: [Frontend in ReactJS](https://github.com/felipebhz/screenshot-react)
*This guide will show how to setup the backend site.*

If you want to use PHPMyAdmin and/or Mailhog you need to **uncomment** the services in the `docker-compose.yaml` file **before** running the commands below.

**First Step:**
- Clone this repository
    - `cd` into the directory where the repository has been cloned
- Run the following commands from the same folder as above
	- On Linux: `docker run --rm -v “$(pwd)”:/app composer install`
    - On Windows: `docker run --rm -v ${pwd}:/app composer install`
*The command will install all dependencies with an official composer container*

    - `docker-compose up -d --force-recreate --build`
*Command to start and build (or rebuild) docker containers*
    - `docker-compose exec app php artisan key:generate`
*This one is necessary to create laravel's key for the application*
    - `docker-compose exec app php artisan optimize`
 *Optimize laravel's files and scripts*
    - `docker-compose exec app php artisan migrate:refresh`
*Run database migrations, deleting the data and reseting the database*
	- Open your web browser and goto http://localhost:8001 to see the landing page.
*Keep in mind this application is the backend / API and will not have anything else visible other than a landing page for entrypoint.*
---

### Troubleshooting 
- Docker can take a while to start, build and load all the dependencies on Windows using WSL2.
- Nginx access log output can be found on `/var/log/nginx/access.log` and error log can be found on `/var/log/nginx/error.log`
- Stop any other services you may have running on your machine before starting docker. Ports can be in conflict.
---
### Softwares used in this application
- PHP 8.1.13
- Laravel 9.43.0
- Nginx 1.21
- Docker Compose 3.8
- MySQL 8.0
- Docker Desktop latest (Windows)
---
## License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

### Short video of the application running
[Video Download](https://drive.google.com/file/d/1eMYkUsUDDJ0p5a9WPXNZWz1BhwmMSch-/view?usp=sharing)
