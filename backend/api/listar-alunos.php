<?php
require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../utils/Logger.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/controllers/AlunoController.php';

try {
    $controller = new AlunoController();
    $controller->listar();
} catch (Exception $e) {
    error_log("Erro ao listar alunos: " . $e->getMessage());
    ApiResponse::error($e->getMessage(), 500);
}
?> 