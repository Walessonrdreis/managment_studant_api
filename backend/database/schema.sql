CREATE DATABASE IF NOT EXISTS calendario_aulas;
USE calendario_aulas;

-- Primeiro, vamos dropar as tabelas na ordem correta (por causa das foreign keys)
DROP TABLE IF EXISTS agendamentos;
DROP TABLE IF EXISTS alunos;
DROP TABLE IF EXISTS disciplinas;

-- Agora criamos as tabelas na ordem correta
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    professor VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT,
    data_aula DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS aluno_disciplina (
    aluno_id INT,
    disciplina_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (aluno_id, disciplina_id),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    disciplina_id INT NULL,
    data_aula DATE NOT NULL,
    horario TIME NOT NULL,
    status ENUM('agendado', 'cancelado', 'realizado') DEFAULT 'agendado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    INDEX idx_data_aula (data_aula)
);

CREATE TABLE IF NOT EXISTS presencas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT,
    aula_id INT,
    presente BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE
);

-- Insere as disciplinas de piano
INSERT INTO disciplinas (nome) VALUES 
    ('Piano Clássico'),
    ('Piano Popular'),
    ('Musicalização com Piano'),
    ('Piano Clássico e Popular');

-- Inserir tipos de aula padrão
INSERT INTO disciplinas (nome) VALUES 
    ('Individual'),
    ('Grupo'),
    ('Online'),
    ('Intensivo');

-- Alterar nome da tabela tipos_aula para disciplinas
RENAME TABLE tipos_aula TO disciplinas;

-- Atualizar referências na tabela agendamentos
ALTER TABLE agendamentos 
DROP FOREIGN KEY agendamentos_ibfk_2,
CHANGE COLUMN tipo_aula_id disciplina_id INT,
ADD CONSTRAINT agendamentos_ibfk_2 
FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id); 