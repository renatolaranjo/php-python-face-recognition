docker-compose -f "docker-compose.yml" up -d --build
docker exec phppythonfacerecog-app cp .env.example .env
docker exec phppythonfacerecog-app npm install 
docker exec phppythonfacerecog-app npm run prod 
docker exec phppythonfacerecog-app composer install
docker exec phppythonfacerecog-app php artisan key:generate
docker exec phppythonfacerecog-app php artisan migrate 