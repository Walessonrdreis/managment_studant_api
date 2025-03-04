<?php
// Arquivo de teste para verificar se a API está funcionando corretamente
header("Content-Type: text/plain");

// Exibir todas as variáveis de ambiente
echo "Variáveis de ambiente:\n";
foreach ($_ENV as $key => $value) {
    echo "$key: $value\n";
}
echo "\n";

// Obter variáveis de ambiente ou usar valores padrão
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'gerenciamento_alunos';
$user = getenv('DB_USER') ?: 'user';
$password = getenv('DB_PASSWORD') ?: 'password';

echo "Parâmetros de conexão:\n";
echo "Host: $host\n";
echo "Database: $dbname\n";
echo "User: $user\n";
echo "Password: " . str_repeat('*', strlen($password)) . "\n\n";

try {
    // Tentar conexão PDO
    echo "Tentando conexão PDO...\n";
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $user, $password, $options);
    echo "Conexão PDO bem-sucedida!\n\n";
    
    // Testar consulta
    echo "Testando consulta...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    if (count($tables) > 0) {
        echo "Tabelas encontradas:\n";
        foreach ($tables as $table) {
            echo "- " . reset($table) . "\n";
        }
    } else {
        echo "Nenhuma tabela encontrada no banco de dados.\n";
    }
    
} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO PDO: " . $e->getMessage() . "\n\n";
}

echo "\nTeste concluído!";
