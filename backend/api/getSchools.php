<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../database/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        SELECT id, nome, logo, is_default 
        FROM escolas 
        ORDER BY nome
    ");
    
    $stmt->execute();
    $escolas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ApiResponse::success(['escolas' => $escolas]);
} catch (Exception $e) {
    error_log('Erro ao listar escolas: ' . $e->getMessage());
    ApiResponse::error('Erro ao listar escolas: ' . $e->getMessage());
}
