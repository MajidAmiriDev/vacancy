put vacancy-mount folder next to the project folder
edit .env file:
-------------------------------------
DB_CONNECTION=mysql
DB_HOST=vacancy_mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD='123qwe!@#QWE'
-------------------------------------
REDIS_CLIENT=predis
REDIS_HOST=vacancy_redis
REDIS_PASSWORD=null
REDIS_PORT=6379
APP_EXPOSED_PORT=8040
NGINX_EXPOSED_PORT=8044
MYSQL_EXPOSED_PORT=33062
REDIS_EXPOSED_PORT=6389
--------------------------------------
docker-compose up -d
docker exec -it vacancy_php composer install
docker exec -it vacancy_php php artisan migrat
docker exec -it vacancy_php php artisan db:seed --class=VacancySeeder
docker exec -it vacancy_php php artisan orchid:install
docker exec -it vacancy_php php artisan orchid:admin admin admin@admin.com password


