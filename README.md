<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Desafio
Temos 2 tipos de usuários, os comuns e lojistas, ambos têm carteira com dinheiro e realizam transferências entre eles. Vamos nos atentar somente ao fluxo de transferência entre dois usuários.

#### Requisitos
- Para ambos tipos de usuário, precisamos do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema. Sendo assim, seu sistema deve permitir apenas um cadastro com o mesmo CPF ou endereço de e-mail.
- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários.
- Lojistas só recebem transferências, não enviam dinheiro para ninguém.
- Validar se o usuário tem saldo antes da transferência.
- Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo, use este mock para simular (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6).
- A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia.
- No recebimento de pagamento, o usuário ou lojista precisa receber notificação (envio de email, sms) enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável. Use este mock para simular o envio (http://o4d9z.mocklab.io/notify).
- Este serviço deve ser RESTFul.

## Documentação
https://drive.google.com/file/d/1g8zgo4umbmswtkeI9P_F44ss0wqjNe9C/view?usp=sharing

## Técnicas usadas para desenvolver o projeto
- SOLID, PSR.
- RESTFull.
- Arquitetura em camadas: Controller, Services, Repository e Entity.
- Designer Pattern Adapter.
  * Adapter é um padrão de projeto estrutural que permite objetos com interfaces incompatíveis colaborarem entre si. O Pattern foi usado para criar uma interface padrão de comunicação com os serviços externos.
- No testes unitários desenvolvido com PHPUnit, foi "mockada" a comunicação com os serviços externos pois não era o objetivo do teste unitário testar a comunicação com os serviços. 

## Melhorias que podem ser aplicadas
- Serviço externo de notificação pode ser processado em Fila.
- Cache de consultas no banco de dados que tem sempre o mesmo retorno.

## Subindo o ambiente local

A api foi desenvolvida utilizando laravel 9 e laravel Sail.

#### Passos
- Clone o projeto. `git clone https://github.com/pabloortolani/desafio-backend.git --config core.autocrlf=input`
- Rode o comando `composer install`
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
- Rode o comando `php artisan key:generate`
- Rode o comando `docker-compose up -d`
- Rode o comando `docker-compose exec api bash` para entrar no container da aplicação.
- Rode o comando `php artisan migrate` para criar as tabelas necessárias para o sistema funcionar.
- Rode o comando `php artisan db:seed` para criar os registros necessários na tabela de tipos de usuários.

## Executar os testes
- Rode o comando `docker-compose exec api bash` para entrar no container da aplicação.
- Rode o comando `php artisan test --filter WalletControllerTest`
