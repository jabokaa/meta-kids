#!/bin/bash

echo "================================"
echo "Setup Meta Kids Docker Project"
echo "================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📄 Criando arquivo .env..."
    cp .env.example .env
    echo "✅ Arquivo .env criado"
else
    echo "✅ Arquivo .env já existe"
fi

echo ""
echo "🐳 Iniciando containers Docker..."
docker-compose up -d

echo ""
echo "⏳ Aguardando banco de dados ficar pronto..."
sleep 10

echo ""
echo "📦 Instalando dependências PHP..."
docker-compose exec -T php composer install

echo ""
echo "🔑 Gerando chave de aplicação..."
docker-compose exec -T php php artisan key:generate

echo ""
echo "🗄️  Executando migrations..."
docker-compose exec -T php php artisan migrate

echo ""
echo "================================"
echo "✅ Setup Completo!"
echo "================================"
echo ""
echo "Acesse a aplicação em: http://localhost"
echo ""
echo "Comandos úteis:"
echo "  - Ver logs: docker-compose logs -f"
echo "  - Parar: docker-compose down"
echo "  - Executar comando: docker-compose exec php php artisan <comando>"
echo ""
