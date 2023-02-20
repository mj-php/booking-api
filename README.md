## Starting App

1. Run `composer install`
2. Run `npm install`
3. Run `cp .env.example .env`
4. Run `docker compose build --pull --no-cache` to build fresh images
5. Run `./vendor/bin/sail up -d` to run images

# Logging and Api

1. Testing users are defined in UserSeeder
2. Postman Collection is provided in project root *Booking Api.postman_collection.json*
