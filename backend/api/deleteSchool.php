<?php
require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../utils/Logger.php';
require_once __DIR__ . '/../database/Database.php';

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

try {
    // Receber ID da escola
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        ApiResponse::badRequest('ID da escola não fornecido ou inválido');
        exit;
    }

    $id = (int)$_GET['id'];
    
    // Verificar se a escola existe
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("SELECT id FROM escolas WHERE id = ?");
    $stmt->execute([$id]);
    
    if (!$stmt->fetch()) {
        ApiResponse::notFound('Escola não encontrada');
        exit;
    }
    
    // Verificar se existem alunos vinculados
    $stmt = $conn->prepare("SELECT COUNT(*) FROM alunos WHERE escola_id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->fetchColumn() > 0) {
        ApiResponse::badRequest('Não é possível excluir a escola pois existem alunos vinculados');
        exit;
    }
    
    // Deletar escola
    $stmt = $conn->prepare("DELETE FROM escolas WHERE id = ?");
    if ($stmt->execute([$id])) {
        ApiResponse::success(null, 'Escola excluída com sucesso');
    } else {
        ApiResponse::error('Não foi possível excluir a escola', 500);
    }
} catch (Exception $e) {
    error_log("Erro ao excluir escola: " . $e->getMessage());
    ApiResponse::error($e->getMessage(), 500);
}