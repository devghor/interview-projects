
## Rest Api Starter

Rest Api Starter will provide you with the tools for making API's that everyone will love, API Authentication is already provided with passport.

***Warnings:*** This project is currently dev stage. Feel free to fork

Here is a list of the packages installed:

- **[Laravel Passport](https://laravel.com/docs/8.x/passport)**
- **[Laratrust](https://github.com/santigarcor/laratrust)**

## Installation

Clone the project by git

```bash
git clone https://github.com/devghor/rest-api-starter.git
```
Install dependency
```bash
composer install
```
Copy .env.example to .env
```bash
cp .env.example .env
```
Generate app key
```bash
php artisan key:generate
```
Change the database username and password
```bash
DB_USERNAME=laravel
DB_PASSWORD=password
```
Migrate and seed the database

```bash
php artisan migrate --seed
```
Make passport client

```bash
php artisan passport:install
```
Copy client id and secret to .env

```bash
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=
```
Start server

```bassh
php artisan serve
```
## License

The Laravel API Starter kit is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
