# API REST - Produtos e Pedidos (Laravel 11)

API simples desenvolvida em **Laravel 11** para gerenciamento de **produtos** e **pedidos**.

---

## Como rodar o projeto

```bash
git clone https://github.com/<seu-usuario>/<nome-do-repo>.git
cd <nome-do-repo>
composer install
cp .env.example .env
php artisan key:generate
```

Edite o arquivo .env e configure o banco de dados MySQL.

Depois, rode as migrations e seeds:

```bash
php artisan migrate --seed
```

## Migrations e Seeds

As migrations criam as tabelas produtos e pedidos.

Os seeds inserem:

    Produtos de exemplo

    Um usuário para autenticação de teste

Credenciais do usuário seedado:

email: teste@example.com
senha: senha123

## Executando a API

php artisan serve

A aplicação estará disponível em:

http://127.0.0.1:8000/api

## Autenticação

Endpoints protegidos usam Laravel Sanctum.
Para autenticar:

POST /api/login
Content-Type: application/json

{
  "email": "teste@example.com",
  "password": "senha123"
}

A resposta conterá o token de acesso.
Use-o nas requisições seguintes:

Authorization: Bearer <token>

## Endpoints principais
Produtos

GET    /api/produtos
POST   /api/produtos
GET    /api/produtos/{id}
PUT    /api/produtos/{id}
DELETE /api/produtos/{id}

Pedidos

GET    /api/pedidos
POST   /api/pedidos
GET    /api/pedidos/{id}
PUT    /api/pedidos/{id}
POST   /api/pedidos/{id}/cancel

⚙️ Regras básicas

    Não é possível excluir produtos com pedidos associados.

    Pedidos só podem ser visualizados, editados ou cancelados pelo criador.

    Cancelar um pedido altera seu status, sem removê-lo do banco.

