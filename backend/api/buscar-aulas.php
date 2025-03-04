<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/controllers/AlunoController.php';

try {
    if (!isset($_GET['aluno_id']) || !is_numeric($_GET['aluno_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID do aluno não fornecido ou inválido'
        ]);
        exit;
    }

    $controller = new AlunoController();
    $result = $controller->buscarAulas((int)$_GET['aluno_id']);
    
    // Garante que as datas estão no formato correto
    if ($result['success'] && isset($result['data']['aulas'])) {
        foreach ($result['data']['aulas'] as &$aula) {
            if (isset($aula['data_aula'])) {
                // Converte a data para o formato Y-m-d
                $data = new DateTime($aula['data_aula']);
                $aula['data_aula'] = $data->format('Y-m-d');
            }
        }
    }
    
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 