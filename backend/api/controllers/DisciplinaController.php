<?php
require_once __DIR__ . '/BaseController.php';

class DisciplinaController extends BaseController {
    private $validationRules = [
        'nome' => [
            'required' => true,
            'min' => 3,
            'max' => 255,
            'message' => 'Nome é obrigatório e deve ter entre 3 e 255 caracteres'
        ]
    ];

    public function listar() {
        try {
            $this->requireMethod('GET');

            $stmt = $this->conn->prepare("
                SELECT id, nome, descricao, created_at, updated_at
                FROM disciplinas
                ORDER BY nome
            ");
            
            $stmt->execute();
            $disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$disciplinas) {
                $disciplinas = [];
            }

            ApiResponse::success(['disciplinas' => $disciplinas]);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    public function adicionar() {
        try {
            $this->requireMethod('POST');
            $data = $this->getRequestData();

            if (!$this->validate($data, $this->validationRules)) {
                ApiResponse::validationError($this->errorMessages);
                return;
            }

            $this->beginTransaction();

            // Verifica se já existe disciplina com mesmo nome
            $stmt = $this->conn->prepare("SELECT id FROM disciplinas WHERE nome = ?");
            $stmt->execute([$data['nome']]);
            if ($stmt->fetch()) {
                ApiResponse::badRequest("Disciplina já cadastrada");
                return;
            }

            // Insere a disciplina
            $stmt = $this->conn->prepare("
                INSERT INTO disciplinas (nome, descricao)
                VALUES (:nome, :descricao)
            ");

            $stmt->execute([
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'] ?? null
            ]);

            $id = $this->conn->lastInsertId();
            $this->commit();

            ApiResponse::created(['id' => $id], "Disciplina cadastrada com sucesso");
        } catch (Exception $e) {
            $this->rollback();
            $this->handleError($e);
        }
    }

    public function atualizar(int $id) {
        try {
            $this->requireMethod('POST');
            $data = $this->getRequestData();

            if (!$this->validate($data, $this->validationRules)) {
                ApiResponse::validationError($this->errorMessages);
                return;
            }

            $this->beginTransaction();

            // Verifica se a disciplina existe
            $stmt = $this->conn->prepare("SELECT id FROM disciplinas WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                ApiResponse::notFound("Disciplina não encontrada");
                return;
            }

            // Verifica se já existe outra disciplina com mesmo nome
            $stmt = $this->conn->prepare("SELECT id FROM disciplinas WHERE nome = ? AND id != ?");
            $stmt->execute([$data['nome'], $id]);
            if ($stmt->fetch()) {
                ApiResponse::badRequest("Já existe outra disciplina com este nome");
                return;
            }

            // Atualiza a disciplina
            $stmt = $this->conn->prepare("
                UPDATE disciplinas 
                SET nome = :nome,
                    descricao = :descricao,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'] ?? null,
                ':id' => $id
            ]);

            $this->commit();
            ApiResponse::success(null, "Disciplina atualizada com sucesso");
        } catch (Exception $e) {
            $this->rollback();
            $this->handleError($e);
        }
    }

    public function excluir(int $id) {
        try {
            $this->requireMethod('POST');
            $this->beginTransaction();

            // Verifica se a disciplina existe
            $stmt = $this->conn->prepare("SELECT id FROM disciplinas WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                ApiResponse::notFound("Disciplina não encontrada");
                return;
            }

            // Verifica se existem alunos vinculados
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) FROM aluno_disciplina WHERE disciplina_id = ?
            ");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                ApiResponse::badRequest("Não é possível excluir a disciplina pois existem alunos vinculados");
                return;
            }

            // Remove a disciplina
            $stmt = $this->conn->prepare("DELETE FROM disciplinas WHERE id = ?");
            $stmt->execute([$id]);

            $this->commit();
            ApiResponse::success(null, "Disciplina excluída com sucesso");
        } catch (Exception $e) {
            $this->rollback();
            $this->handleError($e);
        }
    }
} 