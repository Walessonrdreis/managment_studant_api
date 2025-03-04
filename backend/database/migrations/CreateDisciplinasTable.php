<?php

class CreateDisciplinasTable {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function up() {
        $conn = $this->db->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS disciplinas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            descricao TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_nome (nome)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

        try {
            $conn->exec($sql);

            // Inserir disciplinas padrão
            $disciplinas = [
                'Piano Clássico',
                'Piano Popular',
                'Musicalização com Piano',
                'Piano Clássico e Popular',
                'Individual',
                'Grupo',
                'Online',
                'Intensivo'
            ];

            $stmt = $conn->prepare("INSERT IGNORE INTO disciplinas (nome) VALUES (?)");
            
            foreach ($disciplinas as $disciplina) {
                $stmt->execute([$disciplina]);
            }

        } catch (PDOException $e) {
            throw new Exception("Erro ao criar tabela disciplinas: " . $e->getMessage());
        }
    }

    public function down() {
        $conn = $this->db->getConnection();

        try {
            // Verificar dependências antes de dropar a tabela
            $checkAlunos = $conn->query("SELECT COUNT(*) FROM information_schema.tables 
                                       WHERE table_schema = DATABASE() 
                                       AND table_name = 'aluno_disciplina'");
            
            if ($checkAlunos->fetchColumn() > 0) {
                $conn->exec("DROP TABLE IF EXISTS aluno_disciplina");
            }

            $conn->exec("DROP TABLE IF EXISTS disciplinas");
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir tabela disciplinas: " . $e->getMessage());
        }
    }
} 