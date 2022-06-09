<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Subindo o ambiente local

- Clone o projeto.
- Rode os comandos `composer install` e `php artisan key:generate`
- Renomeie o arquivo `.env.example` para `.env` e altere/crie as seguintes variáveis.
  * DB_CONNECTION=mysql
  * DB_HOST=mysql
  * DB_PORT=3306
  * DB_DATABASE=desafio_backend
  * DB_USERNAME=pabloortolani
  * DB_PASSWORD=123456
  * SERVICE_AUTHORIZING_BASE_URL=https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6
  * SERVICE_NOTIFY_BASE_URL=http://o4d9z.mocklab.io/notify
  * WWWGROUP=1000
  * WWWUSER=1000
- Rode o comando `docker-compose up –d`
- Rode o comando `docker-compose exec api bash` para entrar no container da aplicação.
- Rode o comando `php artisan migrate` para criar as tabelas necessárias para o sistema funcionar.
- Rode o comando `php artisan db:seed` para criar os registros necessários na tabela de tipos de usuários.
