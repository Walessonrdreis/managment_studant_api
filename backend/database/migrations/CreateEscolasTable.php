<?php

class CreateEscolasTable {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function up() {
        $conn = $this->db->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS escolas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            logo VARCHAR(255) NOT NULL,
            endereco TEXT,
            telefone VARCHAR(20),
            email VARCHAR(255),
            observacoes TEXT,
            is_default BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_nome (nome),
            UNIQUE KEY unique_email (email)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

        try {
            $conn->exec($sql);

            // Inserir escola padrão se não existir
            $checkStmt = $conn->query("SELECT COUNT(*) FROM escolas WHERE is_default = TRUE");
            if ($checkStmt->fetchColumn() == 0) {
                $insertSql = "INSERT INTO escolas (nome, logo, is_default) VALUES 
                             ('Escola Padrão', 'default_logo.png', TRUE)";
                $conn->exec($insertSql);
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar tabela escolas: " . $e->getMessage());
        }
    }

    public function down() {
        $conn = $this->db->getConnection();

        try {
            // Verificar e dropar todas as tabelas dependentes primeiro
            $tables = [
                'presencas',
                'aluno_disciplina',
                'agendamentos',
                'aulas',
                'alunos',
                'escolas'
            ];

            foreach ($tables as $table) {
                if ($this->tableExists($conn, $table)) {
                    $conn->exec("DROP TABLE IF EXISTS {$table}");
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir tabela escolas: " . $e->getMessage());
        }
    }

    private function tableExists($conn, $tableName) {
        try {
            $result = $conn->query("
                SELECT COUNT(*)
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
                AND table_name = '{$tableName}'
            ");
            return (int)$result->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
} 