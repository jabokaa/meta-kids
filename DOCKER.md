# Docker Setup para Meta Kids

Este projeto utiliza Docker e Docker Compose para facilitar o desenvolvimento com PHP 8.3, MySQL 8.0 e Nginx.

## Pré-requisitos

- Docker e Docker Compose instalados
- Linux/Mac ou WSL2 no Windows

## Estrutura dos Containers

- **PHP FPM**: Container com PHP 8.3 e extensões necessárias
- **MySQL**: Banco de dados MySQL 8.0
- **Nginx**: Servidor web Nginx para servir a aplicação

## Iniciando o Projeto

### 1. Clone ou configure o projeto:

```bash
cd /home/jabo/bradev/meta-kids
```

### 2. Copie o arquivo .env:

Se você ainda não tem um arquivo `.env`, copie do exemplo:

```bash
cp .env.example .env
```

Se precisar gerar uma chave de aplicação:

```bash
docker-compose exec php php artisan key:generate
```

### 3. Inicie os containers:

```bash
docker-compose up -d
```

### 4. Instale as dependências (se necessário):

```bash
docker-compose exec php composer install
```

### 5. Execute as migrations:

```bash
docker-compose exec php php artisan migrate
```

## Comandos Úteis

### Parar todos os containers:
```bash
docker-compose down
```

### Ver logs:
```bash
docker-compose logs -f
```

### Executar comandos PHP/Artisan:
```bash
docker-compose exec php php artisan <comando>
```

### Acessar o MySQL:
```bash
docker-compose exec mysql mysql -u meta_kids_user -p meta_kids
# Senha: meta_kids_password
```

### Reconstruir os containers:
```bash
docker-compose up -d --build
```

## Acessar a Aplicação

A aplicação estará disponível em: **http://localhost**

O banco de dados MySQL estará disponível em: **localhost:3306**

## Estrutura de Diretórios Docker

```
docker/
├── nginx/
│   ├── nginx.conf          # Configuração principal do Nginx
│   └── conf.d/
│       └── default.conf    # Virtual host padrão
├── php/
│   └── php.ini            # Configurações customizadas do PHP
└── mysql/
    └── init.sql           # Script de inicialização do MySQL
```

## Troubleshooting

### Erro de permissões no storage:
```bash
docker-compose exec php chmod -R 775 storage bootstrap/cache
```

### Resetar o banco de dados:
```bash
docker-compose down -v
docker-compose up -d
docker-compose exec php php artisan migrate
```

### Ver status dos containers:
```bash
docker-compose ps
```

## Variáveis de Ambiente

As variáveis de ambiente são definidas no arquivo `.env` e também no `docker-compose.yml`. O arquivo `.env` tem prioridade.

Importantes:
- `APP_KEY`: Chave de criptografia da aplicação (gerar com artisan)
- `DB_HOST`: Sempre deve ser `mysql` quando usando Docker
- `DB_CONNECTION`: Deve ser `mysql`

## Documentação

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
