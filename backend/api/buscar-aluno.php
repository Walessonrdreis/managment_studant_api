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

    // Busca os dados do aluno incluindo disciplinas e logo da escola
    $stmt = $conn->prepare("
        SELECT 
            a.*,
            e.nome as escola_nome,
            e.logo as escola_logo,
            GROUP_CONCAT(DISTINCT d.id) as disciplina_ids,
            GROUP_CONCAT(DISTINCT d.nome) as disciplina_nomes
        FROM alunos a
        LEFT JOIN escolas e ON a.escola_id = e.id
        LEFT JOIN aluno_disciplina ad ON a.id = ad.aluno_id
        LEFT JOIN disciplinas d ON ad.disciplina_id = d.id
        WHERE a.id = :aluno_id
        GROUP BY a.id, e.nome, e.logo
    ");
    
    $stmt->execute([':aluno_id' => $alunoId]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$aluno) {
        ApiResponse::notFound('Aluno não encontrado');
        exit;
    }

    // Processa as disciplinas
    $disciplinas = [];
    if ($aluno['disciplina_ids'] && $aluno['disciplina_nomes']) {
        $ids = explode(',', $aluno['disciplina_ids']);
        $nomes = explode(',', $aluno['disciplina_nomes']);
        
        for ($i = 0; $i < count($ids); $i++) {
            if (isset($ids[$i]) && isset($nomes[$i])) {
                $disciplinas[] = [
                    'id' => (int)$ids[$i],
                    'nome' => $nomes[$i]
                ];
            }
        }
    }

    // Remove os campos temporários e adiciona o array de disciplinas
    unset($aluno['disciplina_ids']);
    unset($aluno['disciplina_nomes']);
    $aluno['disciplinas'] = $disciplinas;

    ApiResponse::success(['aluno' => $aluno]);
} catch (Exception $e) {
    error_log('Erro ao buscar aluno: ' . $e->getMessage());
    ApiResponse::error($e->getMessage());
}
?> 