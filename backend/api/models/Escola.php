<?php

class Escola {
    private $db;
    private $data;
    private $errors = [];

    public function __construct($data = null) {
        $this->db = new Database();
        $this->data = $data;
    }

    public function validate() {
        $this->errors = [];

        // Validar nome
        if (empty($this->data['nome'])) {
            $this->errors['nome'] = 'Nome é obrigatório';
        } elseif (strlen($this->data['nome']) > 255) {
            $this->errors['nome'] = 'Nome não pode ter mais que 255 caracteres';
        }

        // Validar email se fornecido
        if (!empty($this->data['email'])) {
            if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Email inválido';
            } elseif (strlen($this->data['email']) > 255) {
                $this->errors['email'] = 'Email não pode ter mais que 255 caracteres';
            }
        }

        // Validar telefone se fornecido
        if (!empty($this->data['telefone'])) {
            if (!preg_match('/^\(\d{2}\) \d{4,5}-\d{4}$/', $this->data['telefone'])) {
                $this->errors['telefone'] = 'Telefone deve estar no formato (XX) XXXXX-XXXX';
            }
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function save() {
        if (!$this->validate()) {
            throw new Exception("Dados inválidos: " . json_encode($this->errors));
        }

        $conn = $this->db->getConnection();

        try {
            $conn->beginTransaction();

            // Verificar se já existe escola com mesmo nome ou email
            $checkStmt = $conn->prepare("SELECT id FROM escolas WHERE nome = ? OR (email = ? AND email IS NOT NULL)");
            $checkStmt->execute([$this->data['nome'], $this->data['email'] ?? null]);
            
            if ($checkStmt->fetch()) {
                throw new Exception("Já existe uma escola com este nome ou email");
            }

            $stmt = $conn->prepare("
                INSERT INTO escolas (nome, logo, endereco, telefone, email, observacoes)
                VALUES (:nome, :logo, :endereco, :telefone, :email, :observacoes)
            ");

            $stmt->execute([
                ':nome' => $this->data['nome'],
                ':logo' => $this->data['logo'] ?? null,
                ':endereco' => $this->data['endereco'] ?? null,
                ':telefone' => $this->data['telefone'] ?? null,
                ':email' => $this->data['email'] ?? null,
                ':observacoes' => $this->data['observacoes'] ?? null
            ]);

            $id = $conn->lastInsertId();
            $conn->commit();
            
            return $id;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function update($id) {
        if (!$this->validate()) {
            throw new Exception("Dados inválidos: " . json_encode($this->errors));
        }

        $conn = $this->db->getConnection();

        try {
            $conn->beginTransaction();

            // Verificar se a escola existe
            $checkStmt = $conn->prepare("SELECT is_default FROM escolas WHERE id = ?");
            $checkStmt->execute([$id]);
            $escola = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$escola) {
                throw new Exception("Escola não encontrada");
            }

            // Verificar se já existe outra escola com mesmo nome ou email
            $checkDuplicateStmt = $conn->prepare("
                SELECT id FROM escolas 
                WHERE (nome = ? OR (email = ? AND email IS NOT NULL))
                AND id != ?
            ");
            $checkDuplicateStmt->execute([
                $this->data['nome'],
                $this->data['email'] ?? null,
                $id
            ]);
            
            if ($checkDuplicateStmt->fetch()) {
                throw new Exception("Já existe outra escola com este nome ou email");
            }

            $stmt = $conn->prepare("
                UPDATE escolas 
                SET nome = :nome,
                    logo = :logo,
                    endereco = :endereco,
                    telefone = :telefone,
                    email = :email,
                    observacoes = :observacoes,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $id,
                ':nome' => $this->data['nome'],
                ':logo' => $this->data['logo'],
                ':endereco' => $this->data['endereco'] ?? null,
                ':telefone' => $this->data['telefone'] ?? null,
                ':email' => $this->data['email'] ?? null,
                ':observacoes' => $this->data['observacoes'] ?? null
            ]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public static function delete($id) {
        $db = new Database();
        $conn = $db->getConnection();

        try {
            $conn->beginTransaction();

            // Verificar se a escola existe e não é padrão
            $checkStmt = $conn->prepare("SELECT is_default FROM escolas WHERE id = ?");
            $checkStmt->execute([$id]);
            $escola = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$escola) {
                throw new Exception("Escola não encontrada");
            }

            if ($escola['is_default']) {
                throw new Exception("Não é possível excluir a escola padrão");
            }

            // Verificar se existem alunos vinculados
            $checkAlunosStmt = $conn->prepare("SELECT COUNT(*) FROM alunos WHERE escola_id = ?");
            $checkAlunosStmt->execute([$id]);
            
            if ($checkAlunosStmt->fetchColumn() > 0) {
                throw new Exception("Não é possível excluir a escola pois existem alunos vinculados");
            }

            $stmt = $conn->prepare("DELETE FROM escolas WHERE id = ?");
            $stmt->execute([$id]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public static function find($id) {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("
            SELECT id, nome, logo, endereco, telefone, email, observacoes, is_default, 
                   created_at, updated_at 
            FROM escolas 
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll($search = '', $limit = 10, $offset = 0) {
        $db = new Database();
        $conn = $db->getConnection();

        $where = '';
        $params = [];

        if ($search) {
            $where = "WHERE nome LIKE ? OR endereco LIKE ? OR email LIKE ? OR telefone LIKE ?";
            $searchParam = "%{$search}%";
            $params = [$searchParam, $searchParam, $searchParam, $searchParam];
        }

        // Buscar total
        $countStmt = $conn->prepare("SELECT COUNT(*) FROM escolas " . $where);
        if ($params) {
            $countStmt->execute($params);
        } else {
            $countStmt->execute();
        }
        $total = $countStmt->fetchColumn();

        // Buscar dados
        $query = "
            SELECT id, nome, logo, endereco, telefone, email, observacoes, is_default,
                   created_at, updated_at 
            FROM escolas 
            {$where}
            ORDER BY nome ASC 
            LIMIT ? OFFSET ?
        ";

        $stmt = $conn->prepare($query);
        $params[] = $limit;
        $params[] = $offset;
        $stmt->execute($params);

        return [
            'escolas' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset
        ];
    }
} 