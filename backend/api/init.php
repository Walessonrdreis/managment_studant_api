<?php
// Carregar o bootstrap que configura as variáveis de ambiente
require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../utils/ApiResponse.php';

// Obtém a instância de configuração
$config = Config::getInstance();

// Configura o PHP
ini_set('display_errors', $config->get('api.display_errors') ? 'on' : 'off');
error_reporting($config->get('api.error_reporting', E_ALL));

// Define o tipo de conteúdo e charset
header('Content-Type: ' . $config->get('api.content_type', 'application/json') . '; charset=' . $config->get('api.charset', 'UTF-8'));

// Configura CORS
$corsConfig = $config->get('api.cors');
header('Access-Control-Allow-Origin: ' . implode(', ', $corsConfig['allowed_origins']));
header('Access-Control-Allow-Methods: ' . implode(', ', $corsConfig['allowed_methods']));
header('Access-Control-Allow-Headers: ' . implode(', ', $corsConfig['allowed_headers']));
header('Access-Control-Max-Age: ' . $corsConfig['max_age']);

// Se for uma requisição OPTIONS, retorna apenas os headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configura o manipulador de erros
function handleError($errno, $errstr, $errfile, $errline) {
    $config = Config::getInstance();
    
    if ($config->get('api.debug', false)) {
        ApiResponse::error($errstr, 500);
    } else {
        ApiResponse::error('Erro interno do servidor', 500);
    }
}

// Configura o manipulador de exceções
function handleException($e) {
    $config = Config::getInstance();
    
    if ($config->get('api.debug', false)) {
        ApiResponse::error($e->getMessage(), 500);
    } else {
        ApiResponse::error('Erro interno do servidor', 500);
    }
}

// Registra os manipuladores
set_error_handler('handleError');
set_exception_handler('handleException'); 