# Pré-requisitos
- Composer
- Servidor de banco de dados (PostgresSQL)
- Laravel

```
PHP >= 8.0
```

## Configuração inicial
### 1. Clonar o repositório
```
git clone https://github.com/ItaloSaid/projeto-teste.git
```

### 2. Instalar dependências
```
composer install
```

### 3. Configurar ambiente
```
- Duplique o arquivo .env.example e renomeie a cópia para .env.
- Edite o arquivo .env com as configurações do seu banco de dados:
```

### 4. Gerar chave da aplicação
```
php artisan key:generate
```

### 5. Executar migrações e seeds
O comando abaixo criará as tabelas necessárias no seu banco de dados e populará com dados de teste.                                                                  
    
    php artisan migrate --seed


## Execução
#### 1. Servidor de desenvolvimento
Execute o servidor de desenvolvimento integrado do Laravel:

    php artisan serve

Acesse http://127.0.0.1:8000 no seu navegador para ver o projeto em ação.

## Testes
Para garantir que tudo está funcionando conforme o esperado, executar testes é crucial.

#### Executando todos os testes:
```shell script 
./vendor/bin/phpunit
```
 
### Executando um teste específico:
```shell script 
./vendor/bin/phpunit tests/Unit/NomeDoTeste.php
```