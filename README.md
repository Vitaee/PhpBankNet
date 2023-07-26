<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Project Initialization

First of all please craete the database schema you want to use. 
- I used `banknet_dev` for development, `banknet_test` for unit testing.

I assume you have docker installed on your computer if it so please run below commands:
- `docker compose build`, will build the project.
- `docker compose up`, and your project is ready and awaiting your requests at `localhost:8080`!

### Run unit tests
Firstly, connect to running docker container:
- `sudo docker exec -it php /bin/sh` then simple run test command: `php artisan test`.

I created unit tests and postman doc for this project and i tried to apply best practices. I hope you will like this project.

### Passport Authentication
If you face any error about the authentication you may need to run `php artisan passport:install` and then you will need to copy paste outputs to your `.env` file.

sample output of passport:install:

```bash
Personal access client created successfully.
Client ID: 99bcd175-3ac8-4a6b-bbfa-d8271a2ec1ec
Client secret: kJWDbpBdwughu6WWHi5uoI9TyAkl6BAOY5w4EUrB
Password grant client created successfully.
Client ID: 99bcd175-b715-4b1a-b9a7-73e4e845fe08
Client secret: tPh6HJ5WLS0S0EUvGyhkSZWMaOy9Ht9NWmGYZULX
```

Copy the ``first ID`` and ``` first Secret``` to your env file like below:
```env
PASSPORT_PERSONAL_ACCESS_CLIENT_ID="99bcd175-3ac8-4a6b-bbfa-d8271a2ec1ec"
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="kJWDbpBdwughu6WWHi5uoI9TyAkl6BAOY5w4EUrB"
```
