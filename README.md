# MotionSync

## Requirements and setup
- Install Laravel. `https://laravel.com/docs/13.x/installation`
- Run `composer install` and `npm install` to install the required packages and build the CSS.
- Copy .env.example to .env, set SQL credentials. 
- Run `php artisan key:generate` to generate laravel's key (required for encryption; will fail without)
- Run `php artisan migrate` to migrate the tables.
- Optional, but recommended: Run `php artisan db:seed` to seed the database with test users. Their password will be "GGG".
