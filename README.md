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


![alt text](https://s6.uupload.ir/files/screenshot_from_2023-08-31_17-54-46_kt3w.png?raw=true)

![alt text](https://s6.uupload.ir/files/screenshot_from_2023-08-31_17-55-01_wkn.png?raw=true)

![alt text](https://s6.uupload.ir/files/screenshot_from_2023-08-31_17-55-46_5zh7.png?raw=true)

![alt text](https://s6.uupload.ir/files/screenshot_from_2023-08-31_17-55-59_3zln.png?raw=true)

![alt text](https://s6.uupload.ir/files/screenshot_from_2023-08-31_17-56-13_fs58.png?raw=true)

![alt text](https://s6.uupload.ir/files/screenshot_from_2023-08-31_17-56-35_m4zx.png?raw=true)




