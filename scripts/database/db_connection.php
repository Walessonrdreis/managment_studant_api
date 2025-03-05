<?php

require __DIR__ . '/../../api/vendor/autoload.php'; // Carrega o autoload do Composer

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv; // Adicionando a importação do Dotenv

// Carregar o arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../api'); // Ajuste o caminho conforme necessário
$dotenv->load();

// Configuração do banco de dados
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'seu_banco_de_dados'),
    'username' => env('DB_USERNAME', 'seu_usuario'),
    'password' => env('DB_PASSWORD', 'sua_senha'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

// Configura o Eloquent para usar o Capsule
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Códigos de cor
$green = "\033[32m"; // Verde
$red = "\033[31m";   // Vermelho
$reset = "\033[0m";  // Resetar cor

// Testar a conexão
try {
    $connection = $capsule::connection();
    $connection->getPdo();
    
    // Obtendo detalhes da conexão
    $dbName = $connection->getDatabaseName();
    $host = $connection->getConfig('host');
    $username = $connection->getConfig('username');

    echo "{$green}✅ Conexão com o banco de dados estabelecida com sucesso!{$reset}\n";
    echo "{$green}🔍 Detalhes da Conexão:{$reset}\n";
    echo "{$green}📦 Banco de Dados: {$dbName}{$reset}\n";
    echo "{$green}🌐 Host: {$host}{$reset}\n";
    echo "{$green}👤 Usuário: {$username}{$reset}\n";
} catch (\Exception $e) {
    echo "{$red}❌ Erro ao conectar ao banco de dados: {$e->getMessage()}{$reset}\n";
    echo "{$red}📝 Código do Erro: {$e->getCode()}{$reset}\n";
    echo "{$red}📜 Log do Erro: {$e->getTraceAsString()}{$reset}\n"; // Exibe a pilha de chamadas
}
