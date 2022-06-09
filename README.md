<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Subindo o ambiente local

- Clone o projeto.
- Crie um arquivo .env na pasta raiz do projeto.
- Crie um banco de dados local e configure o mesmo no arquivo .env.
  `DB_DATABASE, DB_USERNAME, DB_PASSWORD`
- Acrescente as seguintes variáveis no arquivo .env:
`SERVICE_AUTHORIZING_BASE_URL=https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6`
`SERVICE_NOTIFY_BASE_URL=http://o4d9z.mocklab.io/notify`
- Rode o comando `php artisan migrate` para criar as tabelas necessárias para o sistema funcionar.
- Rode o comando `php artisan db:seed` para criar os registros necessários na tabela de tipos de usuários.
