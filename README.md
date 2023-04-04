# Pet Shop eCommerce

## Introduction
This project is an API for a Pet Shop eCommerce using [JWT](https://github.com/firebase/php-jwt) authentication and was made using [Laravel 10](https://laravel.com/docs/10.x/releases) and [PHP 8.2](https://www.php.net/releases/8.2/en.php).

## What you will find

### Endpoints

- Admin endpoints
  - Login, logout, create/edit/delete/listing users.
- User endpoints
    - Login, logout, forgot password, reset password, create/edit/delete/fetch users.
- Main page endpoints
  - Listing posts/promotions, fetch post.
- Categories endpoints
  - listing/create/edit/delete/fetch categories.
- Brands endpoints
    - listing/create/edit/delete/fetch brands.
- Products endpoints
    - listing/create/edit/delete/fetch products.

### Controllers, routes, migrations, models, factories and seeders
You will also find the respective controllers, routes, migrations, models, factories and seeders.

### Currency exchange rate package
As requested, an external package was built and added to the project; exposing an API GET endpoint that receives the **amount** and the **currency** to exchange from the euro.

As I published the package, I decided to install it as a dependency for the project using composer, not installing it only as a local dependency.

The route to exchange the currency will be [{base_url}/api/v1/exchange](http://localhost/api/v1/exchange).

You can see this package documentation [here](https://github.com/teusbarros/currency-exchange). 

### OpenAPI/Swagger documentation for every endpoint
You will find the API documentation at [{base_url}/api/swagger](http://localhost/api/swagger)

## Code quality

This project follows the PSR-12 standard, has passed the Larastan level 8, and has the following phpinsights score:
![Screen Shot 2023-04-04 at 18.21.37.png](..%2F..%2F..%2F..%2F..%2Fvar%2Ffolders%2Fsx%2F24fdzf312597hw80l40j_fm80000gn%2FT%2FTemporaryItems%2FNSIRD_screencaptureui_ByTDbt%2FScreen%20Shot%202023-04-04%20at%2018.21.37.png)

## Installation:

you can either set up the application using the given docker container or using your local environment.

### 1 - Clone the repository

```
git clone https://github.com/teusbarros/pet-shop-ecommerce.git
```
### 2 - Go to the project directory

```
cd pet-shop-ecommerce
```
### 3 - Copy .env.example

```
cp .env.example .env
```

Now you can ***choose one of the following ways*** to run your application: Using your **local environment** or **using docker**.

### 4 - Local environment

Edit the `.env` file adding the information to your database `DB_HOST`.

Add your private key `JWT_PRIVATE` and your public key `JWT_PUBLIC` to the `.env` file. 

(If you don't want to generate a key, just past and copy the [firebase example key](https://github.com/firebase/php-jwt#example-with-rs256-openssl))

For testing edit the `phpinit.xml` file adding the `DB_DATABASE`.

Now just run:

```
composer install

php artisan key:generate

php artisan migrate

php artisan db:seed

php artisan test

php artisan serve
```
The API base URL can be accessed here: [http://localhost/api/v1/](http://localhost/api/v1/)

### 4 - Using docker

Add your private key `JWT_PRIVATE` and your public key `JWT_PUBLIC` to the `.env` file.

(If you don't want to generate a key, just past and copy the [firebase example key](https://github.com/firebase/php-jwt#example-with-rs256-openssl))

Run the following code line to start your docker container. Docker will set up all the required environment and create your main and test database.

```
docker compose up
```

Connect to the container to run commands: 

```
docker exec -it pet-shop-app-1 bash
```

Make sure you are in the `/var/www/html` path and run:
```
composer install

php artisan key:generate

php artisan migrate

php artisan db:seed

php artisan test

php artisan serve
```
The API base URL can be accessed here: [http://localhost/api/v1/](http://localhost/api/v1/)

## License

[MIT](https://choosealicense.com/licenses/mit/)
