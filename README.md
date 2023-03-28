## Setup project:

- Clone the project
- Navigate to `/pet-shop-ecommerce` folder
- Ensure version docker installed is active on host
- Copy .env.example: `cp .env.example .env`
- Start docker containers `docker compose up` (add `-d` to run detached)
- Connect to container to run commands: `docker exec -it pet-shop-app-1 bash`
    - Make sure you are in the `/var/www/html` path
    - Install php dependencies: `composer install`
    - Setup app key: `php artisan key:generate`
    - Migrate database: `php artisan migrate`
    - Seed database: `php artisan db:seed`
    - Start Laravel Queue: `php artisan queue:work`
    - Run tests: `php artisan test`
- The API base URL can be accessed here: `http://localhost/api/v1/`
