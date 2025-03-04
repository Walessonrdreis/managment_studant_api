<?php
// Desativar cache do navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Definir cabeçalhos CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Exibir erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => "Método {$_SERVER['REQUEST_METHOD']} não permitido",
        'timestamp' => date('c')
    ]);
    exit;
}

try {
    // Configurações do banco de dados
    $host = getenv('DB_HOST') ?: 'db-dev';
    $dbname = getenv('DB_NAME') ?: 'student_management_system';
    $username = getenv('DB_USER') ?: 'admin';
    $password = getenv('DB_PASSWORD') ?: 'Desbr@v@dor&s123';
    
    // Conectar ao banco de dados
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Consultar disciplinas
    $stmt = $pdo->prepare("
        SELECT id, nome, descricao, created_at, updated_at
        FROM disciplinas
        ORDER BY nome
    ");
    
    $stmt->execute();
    $disciplinas = $stmt->fetchAll();
    
    if (!$disciplinas) {
        $disciplinas = [];
    }
    
    // Gerar resposta
    echo json_encode([
        'success' => true,
        'data' => ['disciplinas' => $disciplinas],
        'message' => null,
        'timestamp' => date('c')
    ]);
    
} catch (PDOException $e) {
    // Log do erro
    error_log("Erro de banco de dados: " . $e->getMessage());
    
    // Resposta de erro
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Erro ao conectar ao banco de dados: " . $e->getMessage(),
        'timestamp' => date('c')
    ]);
} catch (Exception $e) {
    // Log do erro
    error_log("Erro geral: " . $e->getMessage());
    
    // Resposta de erro
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Erro interno do servidor: " . $e->getMessage(),
        'timestamp' => date('c')
    ]);
}
