<?php
require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../utils/Logger.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/controllers/AlunoController.php';

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        ApiResponse::badRequest('ID do aluno não fornecido ou inválido');
        exit;
    }

    $controller = new AlunoController();
    $controller->atualizar((int)$_GET['id']);
} catch (Exception $e) {
    error_log("Erro ao atualizar aluno: " . $e->getMessage());
    ApiResponse::error($e->getMessage(), 500);
}
?> 