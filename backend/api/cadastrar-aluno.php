<?php
// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/ApiResponse.php';
require_once __DIR__ . '/../utils/Logger.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/controllers/AlunoController.php';

try {
    $controller = new AlunoController();
    $controller->cadastrar();
} catch (Exception $e) {
    error_log("Erro ao cadastrar aluno: " . $e->getMessage());
    ApiResponse::error($e->getMessage(), 500);
} 