// editSchool.php
<?php
require_once '../database/Database.php';
require_once '../models/Escola.php';

header('Content-Type: application/json');

try {
    // Receber dados do POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar ID da escola
    if (!isset($data['id'])) {
        throw new Exception('ID da escola é obrigatório');
    }

    $id = $data['id'];
    unset($data['id']); // Remover ID dos dados a serem atualizados

    // Criar instância do modelo com os dados
    $escola = new Escola($data);

    // Atualizar escola
    $escola->update($id);

    // Retornar resposta
    echo json_encode([
        'success' => true,
        'message' => 'Escola atualizada com sucesso'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
