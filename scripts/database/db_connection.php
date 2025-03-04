<?php

require __DIR__ . '/../../api/vendor/autoload.php'; // Carrega o autoload do Composer

use Illuminate\Database\Capsule\Manager as Capsule;

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

// Testar a conexão
try {
    $capsule::connection()->getPdo();
    echo "Conexão com o banco de dados estabelecida com sucesso!\n";
} catch (\Exception $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage() . "\n";
}
