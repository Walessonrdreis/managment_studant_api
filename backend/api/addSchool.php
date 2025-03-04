// addSchool.php

<?php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/models/Escola.php';

header('Content-Type: application/json');

try {
    // Receber dados do POST
    $jsonData = file_get_contents('php://input');
    
    if (empty($jsonData)) {
        throw new Exception('Nenhum dado foi enviado');
    }

    $data = json_decode($jsonData, true);

    if ($data === null) {
        throw new Exception('Dados inválidos. Verifique se o formato JSON está correto: ' . json_last_error_msg());
    }

    if (empty($data['nome'])) {
        throw new Exception('O nome da escola é obrigatório');
    }

    // Criar instância do modelo com os dados
    $escola = new Escola($data);

    // Salvar escola
    $id = $escola->save();

    // Retornar resposta
    echo json_encode([
        'success' => true,
        'message' => 'Escola cadastrada com sucesso',
        'data' => [
            'id' => $id,
            'nome' => $data['nome']
        ]
    ]);

} catch (Exception $e) {
    error_log('Erro ao cadastrar escola: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}


