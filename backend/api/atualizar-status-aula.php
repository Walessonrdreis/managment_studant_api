<?php
require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../utils/Logger.php';
require_once __DIR__ . '/../database/Database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['aula_id']) || !is_numeric($data['aula_id'])) {
        ApiResponse::badRequest('ID da aula não fornecido ou inválido');
        exit;
    }

    if (!isset($data['status']) || !in_array($data['status'], ['agendado', 'realizado', 'cancelado'])) {
        ApiResponse::badRequest('Status inválido');
        exit;
    }

    $aulaId = (int)$data['aula_id'];
    $status = $data['status'];
    
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Verifica se a aula existe
    $stmt = $conn->prepare("SELECT id FROM agendamentos WHERE id = ?");
    $stmt->execute([$aulaId]);
    
    if (!$stmt->fetch()) {
        ApiResponse::notFound('Aula não encontrada');
        exit;
    }
    
    // Atualiza o status da aula
    $stmt = $conn->prepare("UPDATE agendamentos SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $aulaId])) {
        ApiResponse::success(null, 'Status atualizado com sucesso');
    } else {
        ApiResponse::error('Não foi possível atualizar o status da aula', 500);
    }
} catch (Exception $e) {
    error_log("Erro ao atualizar status da aula: " . $e->getMessage());
    ApiResponse::error($e->getMessage(), 500);
}
?> 