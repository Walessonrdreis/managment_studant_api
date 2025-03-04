<?php

class CreateAlunosTable {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function up() {
        $conn = $this->db->getConnection();

        try {
            // 1. Primeiro criar a tabela principal de alunos
            $sql = "CREATE TABLE IF NOT EXISTS alunos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE,
                telefone VARCHAR(20),
                matricula VARCHAR(20) UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_matricula (matricula),
                INDEX idx_email (email)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            $conn->exec($sql);

            // 2. Criar tabela de aulas
            $sql = "CREATE TABLE IF NOT EXISTS aulas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                disciplina_id INT,
                data_aula DATE NOT NULL,
                hora_inicio TIME NOT NULL,
                hora_fim TIME NOT NULL,
                descricao TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
                INDEX idx_data (data_aula),
                INDEX idx_disciplina (disciplina_id)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            $conn->exec($sql);

            // 3. Criar tabela de agendamentos
            $sql = "CREATE TABLE IF NOT EXISTS agendamentos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                aluno_id INT NOT NULL,
                disciplina_id INT NULL,
                professor_id INT NULL,
                data_aula DATE NOT NULL,
                horario TIME NOT NULL,
                status ENUM('agendado', 'cancelado', 'realizado') DEFAULT 'agendado',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
                FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE SET NULL,
                INDEX idx_data_aula (data_aula),
                INDEX idx_aluno (aluno_id),
                INDEX idx_disciplina (disciplina_id),
                INDEX idx_status (status)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            $conn->exec($sql);

            // 4. Depois criar a tabela de relacionamento aluno_disciplina
            $sql = "CREATE TABLE IF NOT EXISTS aluno_disciplina (
                aluno_id INT,
                disciplina_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (aluno_id, disciplina_id),
                FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
                FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
                INDEX idx_aluno (aluno_id),
                INDEX idx_disciplina (disciplina_id)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            $conn->exec($sql);

            // 5. Por último, criar tabela de presença (que depende de alunos e aulas)
            $sql = "CREATE TABLE IF NOT EXISTS presencas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                aluno_id INT,
                aula_id INT,
                presente BOOLEAN DEFAULT false,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
                FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
                INDEX idx_aluno_aula (aluno_id, aula_id)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            $conn->exec($sql);

        } catch (PDOException $e) {
            throw new Exception("Erro ao criar tabelas relacionadas a alunos: " . $e->getMessage());
        }
    }

    public function down() {
        $conn = $this->db->getConnection();

        try {
            // Dropar tabelas na ordem correta (por causa das foreign keys)
            $conn->exec("DROP TABLE IF EXISTS presencas");
            $conn->exec("DROP TABLE IF EXISTS aluno_disciplina");
            $conn->exec("DROP TABLE IF EXISTS agendamentos");
            $conn->exec("DROP TABLE IF EXISTS aulas");
            $conn->exec("DROP TABLE IF EXISTS alunos");
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir tabelas relacionadas a alunos: " . $e->getMessage());
        }
    }
} 