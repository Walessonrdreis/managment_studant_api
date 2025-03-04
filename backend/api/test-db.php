<?php
// Desativar cache do navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Definir tipo de conteúdo como texto para facilitar a leitura
header('Content-Type: text/plain; charset=utf-8');

// Exibir erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "=== TESTE DE CONEXÃO COM O BANCO DE DADOS ===\n\n";

// Verificar variáveis de ambiente
echo "Variáveis de ambiente:\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'não definido') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ?: 'não definido') . "\n";
echo "DB_USER: " . (getenv('DB_USER') ?: 'não definido') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '******' : 'não definido') . "\n\n";

// Tentar conexão com o banco de dados usando variáveis de ambiente
echo "Tentando conexão com o banco de dados usando variáveis de ambiente...\n";
try {
    $host = getenv('DB_HOST') ?: 'db';
    $dbname = getenv('DB_NAME') ?: 'student_management_system';
    $user = getenv('DB_USER') ?: 'admin';
    $password = getenv('DB_PASSWORD') ?: 'Desbr@v@dor&s123';
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $user, $password, $options);
    echo "Conexão bem-sucedida!\n\n";
    
    // Verificar tabelas existentes
    echo "Tabelas existentes no banco de dados:\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    } else {
        echo "Nenhuma tabela encontrada no banco de dados.\n";
    }
    
    // Verificar dados em algumas tabelas principais
    if (in_array('escolas', $tables)) {
        echo "\nDados da tabela 'escolas':\n";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM escolas");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "Total de registros: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT id, nome, logo, is_default FROM escolas LIMIT 5");
            $escolas = $stmt->fetchAll();
            foreach ($escolas as $escola) {
                echo "  ID: {$escola['id']}, Nome: {$escola['nome']}, Logo: {$escola['logo']}, Default: {$escola['is_default']}\n";
            }
        }
    }
    
    if (in_array('disciplinas', $tables)) {
        echo "\nDados da tabela 'disciplinas':\n";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM disciplinas");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "Total de registros: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT id, nome FROM disciplinas LIMIT 5");
            $disciplinas = $stmt->fetchAll();
            foreach ($disciplinas as $disciplina) {
                echo "  ID: {$disciplina['id']}, Nome: {$disciplina['nome']}\n";
            }
        }
    }
    
    if (in_array('alunos', $tables)) {
        echo "\nDados da tabela 'alunos':\n";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM alunos");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "Total de registros: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT id, nome, email, telefone FROM alunos LIMIT 5");
            $alunos = $stmt->fetchAll();
            foreach ($alunos as $aluno) {
                echo "  ID: {$aluno['id']}, Nome: {$aluno['nome']}, Email: {$aluno['email']}, Telefone: {$aluno['telefone']}\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "ERRO: Não foi possível conectar ao banco de dados.\n";
    echo "Mensagem de erro: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO TESTE ===\n";
?> 