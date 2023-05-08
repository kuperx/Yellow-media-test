# Install instruction

1. `git clone git@github.com:kuperx/Yellow-media-test.git`
2. Copy `.env.example` and rename it to `.env` (Fill mail configurations)
3. Build docker containers `docker-compose up -d`
4. Install dependencies `docker exec -it php-container composer install`
5. Make migrations `docker exec -it php-container php artisan migrate`
5. Run seeds `docker exec -it php-container php artisan db:seed`

Entrypoint - `http://localhost:8080/`
