<?php
require_once __DIR__ . '/controllers/DisciplinaController.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID da disciplina não fornecido'
    ]);
    exit;
}

$controller = new DisciplinaController();
$controller->atualizar((int)$data['id']); 