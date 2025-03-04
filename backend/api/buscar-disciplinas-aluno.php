<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../database/Database.php';

try {
    if (!isset($_GET['id'])) {
        ApiResponse::badRequest('ID do aluno não fornecido');
        exit;
    }

    $alunoId = (int)$_GET['id'];

    $db = Database::getInstance();
    $conn = $db->getConnection();

    // Busca as disciplinas do aluno
    $stmt = $conn->prepare("
        SELECT 
            d.id,
            d.nome
        FROM aluno_disciplina ad
        JOIN disciplinas d ON ad.disciplina_id = d.id
        WHERE ad.aluno_id = :aluno_id
        ORDER BY d.nome
    ");
    
    $stmt->execute([':aluno_id' => $alunoId]);
    $disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ApiResponse::success(['disciplinas' => $disciplinas]);
} catch (Exception $e) {
    error_log('Erro ao buscar disciplinas do aluno: ' . $e->getMessage());
    ApiResponse::error($e->getMessage());
} 