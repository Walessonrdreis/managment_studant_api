<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Aluno.php';

class AlunoController extends BaseController {
    private $validationRules = [
        'nome' => [
            'required' => true,
            'min' => 3,
            'max' => 255,
            'message' => 'Nome é obrigatório e deve ter entre 3 e 255 caracteres'
        ],
        'email' => [
            'required' => true,
            'type' => 'email',
            'max' => 255,
            'message' => 'Email é obrigatório e deve ser válido'
        ],
        'telefone' => [
            'required' => false,
            'pattern' => '/^\(\d{2}\) \d{4,5}-\d{4}$/',
            'message' => 'Telefone deve estar no formato (XX) XXXXX-XXXX'
        ]
    ];

    /**
     * Lista todos os alunos
     */
    public function listar() {
        try {
            $this->requireMethod('GET');

            $stmt = $this->conn->prepare("
                SELECT 
                    a.id,
                    a.nome,
                    a.email,
                    a.telefone,
                    a.matricula,
                    a.escola_id,
                    e.nome as escola_nome,
                    GROUP_CONCAT(DISTINCT d.id) as disciplina_ids,
                    GROUP_CONCAT(DISTINCT d.nome) as disciplinas,
                    (
                        SELECT CONCAT(data_aula, ' ', horario)
                        FROM agendamentos ag
                        WHERE ag.aluno_id = a.id
                        AND CONCAT(data_aula, ' ', horario) >= NOW()
                        ORDER BY data_aula, horario
                        LIMIT 1
                    ) as proxima_aula
                FROM alunos a
                LEFT JOIN escolas e ON a.escola_id = e.id
                LEFT JOIN aluno_disciplina ad ON a.id = ad.aluno_id
                LEFT JOIN disciplinas d ON ad.disciplina_id = d.id
                GROUP BY a.id, a.nome, a.email, a.telefone, a.matricula, a.escola_id, e.nome
                ORDER BY a.nome
            ");

            $stmt->execute();
            $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($alunos as &$aluno) {
                // Processa as disciplinas como array de objetos
                $disciplinaIds = $aluno['disciplina_ids'] ? explode(',', $aluno['disciplina_ids']) : [];
                $disciplinaNomes = $aluno['disciplinas'] ? explode(',', $aluno['disciplinas']) : [];
                $aluno['disciplinas'] = [];
                
                for ($i = 0; $i < count($disciplinaIds); $i++) {
                    if (isset($disciplinaIds[$i]) && isset($disciplinaNomes[$i])) {
                        $aluno['disciplinas'][] = [
                            'id' => (int)$disciplinaIds[$i],
                            'nome' => $disciplinaNomes[$i]
                        ];
                    }
                }

                // Remove campos temporários
                unset($aluno['disciplina_ids']);
                
                $aluno['escola'] = $aluno['escola_nome'] ?? null;
                unset($aluno['escola_nome']);
            }

            ApiResponse::success(['alunos' => $alunos], 'Alunos listados com sucesso');
        } catch (Exception $e) {
            ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Cadastra um novo aluno
     */
    public function cadastrar() {
        try {
            $this->requireMethod('POST');
            $data = $this->getRequestData();
            
            error_log('Iniciando cadastro de aluno');
            error_log('Dados recebidos: ' . json_encode($data));

            // Validação básica dos campos obrigatórios
            if (empty($data['nome']) || empty($data['email'])) {
                $errors = [];
                if (empty($data['nome'])) {
                    $errors['nome'] = 'Nome é obrigatório';
                }
                if (empty($data['email'])) {
                    $errors['email'] = 'Email é obrigatório';
                }
                ApiResponse::validationError($errors);
                return;
            }

            if (!$this->validate($data, $this->validationRules)) {
                ApiResponse::validationError($this->errorMessages);
                return;
            }

            $this->beginTransaction();

            try {
                // Verifica se já existe aluno com mesmo email
                $stmt = $this->conn->prepare("SELECT id FROM alunos WHERE email = ?");
                $stmt->execute([$data['email']]);
                if ($stmt->fetch()) {
                    $this->rollback();
                    ApiResponse::badRequest("Email já cadastrado");
                    return;
                }

                // Gera matrícula única
                $matricula = $this->gerarMatricula();
                error_log('Matrícula gerada: ' . $matricula);

                // Insere o aluno
                $stmt = $this->conn->prepare("
                    INSERT INTO alunos (nome, email, telefone, matricula, escola_id)
                    VALUES (:nome, :email, :telefone, :matricula, :escola_id)
                ");

                $params = [
                    ':nome' => trim($data['nome']),
                    ':email' => trim($data['email']),
                    ':telefone' => isset($data['telefone']) ? trim($data['telefone']) : null,
                    ':matricula' => $matricula,
                    ':escola_id' => isset($data['escola_id']) && !empty($data['escola_id']) ? (int)$data['escola_id'] : null
                ];

                error_log('Parâmetros para inserção do aluno: ' . json_encode($params));
                
                $stmt->execute($params);
                $alunoId = $this->conn->lastInsertId();
                error_log('Aluno inserido com ID: ' . $alunoId);

                // Associa disciplinas se fornecidas
                if (isset($data['disciplinas']) && is_array($data['disciplinas'])) {
                    error_log('Disciplinas a serem associadas: ' . json_encode($data['disciplinas']));
                    
                    // Limpa associações anteriores (caso existam)
                    $stmt = $this->conn->prepare("DELETE FROM aluno_disciplina WHERE aluno_id = ?");
                    $stmt->execute([$alunoId]);
                    
                    // Insere novas associações
                    $stmt = $this->conn->prepare("
                        INSERT INTO aluno_disciplina (aluno_id, disciplina_id)
                        VALUES (?, ?)
                    ");
                    
                    foreach ($data['disciplinas'] as $disciplinaId) {
                        if (!empty($disciplinaId)) {
                            $stmt->execute([$alunoId, (int)$disciplinaId]);
                            error_log("Disciplina $disciplinaId associada ao aluno $alunoId");
                        }
                    }
                }

                // Cria agendamentos se fornecidos
                if (isset($data['aulas']) && is_array($data['aulas'])) {
                    error_log('Aulas a serem agendadas: ' . json_encode($data['aulas']));
                    
                    $stmt = $this->conn->prepare("
                        INSERT INTO agendamentos (aluno_id, disciplina_id, data_aula, horario, status)
                        VALUES (?, ?, ?, ?, 'agendado')
                    ");
                    
                    foreach ($data['aulas'] as $aula) {
                        if (!empty($aula['data']) && !empty($aula['horario']) && !empty($aula['disciplina_id'])) {
                            try {
                                // Converte a data para o formato correto
                                $data_aula = DateTime::createFromFormat('d/m/Y', $aula['data']);
                                if (!$data_aula) {
                                    $data_aula = DateTime::createFromFormat('Y-m-d', $aula['data']);
                                }
                                
                                if ($data_aula) {
                                    $data_formatada = $data_aula->format('Y-m-d');
                                    $stmt->execute([
                                        $alunoId,
                                        (int)$aula['disciplina_id'],
                                        $data_formatada,
                                        $aula['horario']
                                    ]);
                                    error_log("Aula agendada para $data_formatada às {$aula['horario']} para o aluno $alunoId");
                                } else {
                                    error_log("Formato de data inválido: {$aula['data']}");
                                }
                            } catch (Exception $e) {
                                error_log("Erro ao agendar aula: " . $e->getMessage());
                            }
                        }
                    }
                }

                $this->commit();
                error_log('Cadastro finalizado com sucesso');
                
                // Busca os dados completos do aluno para retornar
                $stmt = $this->conn->prepare("
                    SELECT 
                        a.*,
                        e.nome as escola_nome,
                        GROUP_CONCAT(DISTINCT d.nome) as disciplinas
                    FROM alunos a
                    LEFT JOIN escolas e ON a.escola_id = e.id
                    LEFT JOIN aluno_disciplina ad ON a.id = ad.aluno_id
                    LEFT JOIN disciplinas d ON ad.disciplina_id = d.id
                    WHERE a.id = ?
                    GROUP BY a.id
                ");
                
                $stmt->execute([$alunoId]);
                $alunoCompleto = $stmt->fetch(PDO::FETCH_ASSOC);
                
                ApiResponse::created(
                    [
                        'id' => $alunoId,
                        'matricula' => $matricula,
                        'aluno' => $alunoCompleto
                    ],
                    "Aluno cadastrado com sucesso"
                );

            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            error_log('Erro ao cadastrar aluno: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Busca um aluno específico
     * @param int $id ID do aluno
     */
    public function buscar(int $id) {
        try {
            $this->requireMethod('GET');

            $stmt = $this->conn->prepare("
                SELECT 
                    a.*,
                    GROUP_CONCAT(d.nome) as disciplinas
                FROM alunos a
                LEFT JOIN aluno_disciplina ad ON a.id = ad.aluno_id
                LEFT JOIN disciplinas d ON ad.disciplina_id = d.id
                WHERE a.id = :id
                GROUP BY a.id
            ");
            $stmt->execute([':id' => $id]);
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$aluno) {
                ApiResponse::notFound("Aluno não encontrado");
                return;
            }

            $aluno['disciplinas'] = $aluno['disciplinas'] ? explode(',', $aluno['disciplinas']) : [];

            ApiResponse::success(['aluno' => $aluno]);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Atualiza um aluno
     * @param int $id ID do aluno
     */
    public function atualizar(int $id) {
        try {
            $this->requireMethod('POST');
            $data = $this->getRequestData();

            if (!$this->validate($data, $this->validationRules)) {
                ApiResponse::validationError($this->errorMessages);
                return;
            }

            $this->beginTransaction();

            // Verifica se o aluno existe
            $stmt = $this->conn->prepare("SELECT id FROM alunos WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                ApiResponse::notFound("Aluno não encontrado");
                return;
            }

            // Verifica email duplicado
            $stmt = $this->conn->prepare("SELECT id FROM alunos WHERE email = ? AND id != ?");
            $stmt->execute([$data['email'], $id]);
            if ($stmt->fetch()) {
                ApiResponse::badRequest("Email já cadastrado para outro aluno");
                return;
            }

            // Atualiza dados do aluno
            $stmt = $this->conn->prepare("
                UPDATE alunos 
                SET nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    escola_id = :escola_id,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                ':nome' => $data['nome'],
                ':email' => $data['email'],
                ':telefone' => $data['telefone'] ?? null,
                ':escola_id' => isset($data['escola_id']) && !empty($data['escola_id']) ? (int)$data['escola_id'] : null,
                ':id' => $id
            ]);

            // Atualiza disciplinas se fornecidas
            if (isset($data['disciplinas'])) {
                // Remove associações antigas
                $stmt = $this->conn->prepare("DELETE FROM aluno_disciplina WHERE aluno_id = ?");
                $stmt->execute([$id]);

                // Adiciona novas associações
                if (!empty($data['disciplinas'])) {
                    $this->associarDisciplinas($id, $data['disciplinas']);
                }
            }

            // Atualiza as aulas se fornecidas
            if (isset($data['aulas']) && is_array($data['aulas'])) {
                // Remove aulas futuras existentes
                $stmt = $this->conn->prepare("
                    DELETE FROM agendamentos 
                    WHERE aluno_id = ? 
                    AND data_aula >= CURRENT_DATE
                ");
                $stmt->execute([$id]);

                // Insere as novas aulas
                $stmt = $this->conn->prepare("
                    INSERT INTO agendamentos (aluno_id, disciplina_id, data_aula, horario, status)
                    VALUES (?, ?, ?, ?, 'agendado')
                ");

                foreach ($data['aulas'] as $aula) {
                    if (!empty($aula['data']) && !empty($aula['horario'])) {
                        try {
                            // Converte a data para o formato correto
                            $data_aula = DateTime::createFromFormat('Y-m-d', $aula['data']);
                            if (!$data_aula) {
                                $data_aula = DateTime::createFromFormat('d/m/Y', $aula['data']);
                            }

                            if ($data_aula) {
                                $data_formatada = $data_aula->format('Y-m-d');
                                // Usa a primeira disciplina selecionada para todas as aulas
                                $disciplina_id = !empty($data['disciplinas']) ? $data['disciplinas'][0] : null;
                                $stmt->execute([
                                    $id,
                                    $disciplina_id,
                                    $data_formatada,
                                    $aula['horario']
                                ]);
                                error_log("Aula agendada para $data_formatada às {$aula['horario']} para o aluno $id");
                            }
                        } catch (Exception $e) {
                            error_log("Erro ao agendar aula: " . $e->getMessage());
                        }
                    }
                }
            }

            $this->commit();
            ApiResponse::success(null, "Aluno atualizado com sucesso");

        } catch (Exception $e) {
            $this->rollback();
            $this->handleError($e);
        }
    }

    /**
     * Exclui um aluno
     * @param int $id ID do aluno
     */
    public function excluir($id) {
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();

            // Inicia uma transação
            $conn->beginTransaction();

            // Primeiro exclui os agendamentos do aluno
            $stmt = $conn->prepare("DELETE FROM agendamentos WHERE aluno_id = ?");
            $stmt->execute([$id]);

            // Depois exclui o aluno
            $stmt = $conn->prepare("DELETE FROM alunos WHERE id = ?");
            $stmt->execute([$id]);

            // Confirma a transação
            $conn->commit();

            ApiResponse::success(null, 'Aluno excluído com sucesso');
        } catch (Exception $e) {
            // Em caso de erro, desfaz as alterações
            if ($conn) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Gera uma matrícula única para o aluno
     * @return string
     */
    private function gerarMatricula(): string {
        $ano = date('Y');
        $sequencial = 1;

        // Busca a última matrícula do ano
        $stmt = $this->conn->prepare("
            SELECT matricula 
            FROM alunos 
            WHERE matricula LIKE :ano
            ORDER BY matricula DESC 
            LIMIT 1
        ");
        $stmt->execute([':ano' => $ano . '%']);
        $ultimaMatricula = $stmt->fetch(PDO::FETCH_COLUMN);

        if ($ultimaMatricula) {
            // Extrai o número sequencial da última matrícula
            $sequencial = (int)substr($ultimaMatricula, -4) + 1;
        }

        // Formata a matrícula: ANO + 4 dígitos sequenciais
        return sprintf('%d%04d', $ano, $sequencial);
    }

    /**
     * Associa disciplinas a um aluno
     * @param int $alunoId ID do aluno
     * @param array $disciplinas Array com IDs das disciplinas
     */
    private function associarDisciplinas(int $alunoId, array $disciplinas): void {
        $stmt = $this->conn->prepare("
            INSERT INTO aluno_disciplina (aluno_id, disciplina_id)
            VALUES (:aluno_id, :disciplina_id)
        ");

        foreach ($disciplinas as $disciplinaId) {
            try {
                $stmt->execute([
                    ':aluno_id' => $alunoId,
                    ':disciplina_id' => (int)$disciplinaId
                ]);
            } catch (Exception $e) {
                error_log("Erro ao associar disciplina ID '$disciplinaId' ao aluno $alunoId: " . $e->getMessage());
            }
        }
    }

    /**
     * Cria agendamentos para um aluno
     * @param int $alunoId ID do aluno
     * @param array $aulas Array com datas e horários das aulas
     */
    private function criarAgendamentos(int $alunoId, array $aulas): void {
        $stmt = $this->conn->prepare("
            INSERT INTO agendamentos (aluno_id, disciplina_id, data_aula, horario, status)
            VALUES (:aluno_id, :disciplina_id, :data_aula, :horario, 'agendado')
        ");

        foreach ($aulas as $aula) {
            if (empty($aula['data']) || empty($aula['horario']) || empty($aula['disciplina_id'])) {
                error_log("Dados incompletos para agendamento: " . json_encode($aula));
                continue;
            }

            try {
                // Tenta converter a data para o formato correto
                $data = null;
                
                // Tenta primeiro o formato brasileiro
                $data = DateTime::createFromFormat('d/m/Y', $aula['data']);
                if (!$data) {
                    // Tenta o formato ISO
                    $data = DateTime::createFromFormat('Y-m-d', $aula['data']);
                }
                
                if (!$data) {
                    error_log("Data inválida: " . $aula['data']);
                    continue;
                }

                // Formata a data para o formato do banco
                $dataFormatada = $data->format('Y-m-d');

                // Valida o formato do horário
                if (!preg_match('/^\d{2}:\d{2}$/', $aula['horario'])) {
                    error_log("Horário inválido: " . $aula['horario']);
                    continue;
                }

                // Valida a disciplina
                if (!is_numeric($aula['disciplina_id'])) {
                    error_log("ID de disciplina inválido: " . $aula['disciplina_id']);
                    continue;
                }

                $params = [
                    ':aluno_id' => $alunoId,
                    ':disciplina_id' => (int)$aula['disciplina_id'],
                    ':data_aula' => $dataFormatada,
                    ':horario' => $aula['horario']
                ];

                error_log("Tentando criar agendamento com params: " . json_encode($params));
                
                $stmt->execute($params);
            } catch (Exception $e) {
                error_log("Erro ao criar agendamento: " . $e->getMessage());
                error_log("Dados da aula: " . json_encode($aula));
            }
        }
    }

    private function limparDisciplinasDuplicadas($disciplinas) {
        // Cria um array associativo usando o nome da disciplina como chave
        $disciplinasUnicas = [];
        foreach ($disciplinas as $disciplina) {
            $nome = trim($disciplina['nome']);
            if (!isset($disciplinasUnicas[$nome])) {
                $disciplinasUnicas[$nome] = $disciplina;
            }
        }
        return array_values($disciplinasUnicas);
    }

    public function buscarAulas(int $alunoId) {
        try {
            // Primeiro, busca as disciplinas atuais do aluno
            $stmtDisciplinas = $this->conn->prepare("
                SELECT DISTINCT d.id, d.nome
                FROM aluno_disciplina ad
                JOIN disciplinas d ON ad.disciplina_id = d.id
                WHERE ad.aluno_id = :aluno_id
                ORDER BY d.nome
            ");
            $stmtDisciplinas->execute([':aluno_id' => $alunoId]);
            $disciplinasAluno = $stmtDisciplinas->fetchAll(PDO::FETCH_ASSOC);
            
            // Remove disciplinas duplicadas
            $disciplinasAluno = $this->limparDisciplinasDuplicadas($disciplinasAluno);
            
            error_log("Disciplinas atuais do aluno (após limpeza): " . print_r($disciplinasAluno, true));
            
            // Busca as aulas
            $stmt = $this->conn->prepare("
                SELECT 
                    ag.id,
                    ag.data_aula,
                    ag.horario,
                    ag.status,
                    ag.disciplina_id,
                    d.nome as disciplina_nome
                FROM agendamentos ag
                LEFT JOIN disciplinas d ON ag.disciplina_id = d.id
                WHERE ag.aluno_id = :aluno_id
                ORDER BY ag.data_aula ASC, ag.horario ASC
            ");
            
            $stmt->execute([':aluno_id' => $alunoId]);
            $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Busca os dados do aluno
            $stmtAluno = $this->conn->prepare("
                SELECT 
                    a.*,
                    e.nome as escola_nome,
                    GROUP_CONCAT(DISTINCT d.nome ORDER BY d.nome) as disciplinas_nomes
                FROM alunos a
                LEFT JOIN escolas e ON a.escola_id = e.id
                LEFT JOIN aluno_disciplina ad ON a.id = ad.aluno_id
                LEFT JOIN disciplinas d ON ad.disciplina_id = d.id
                WHERE a.id = :aluno_id
                GROUP BY a.id
            ");
            
            $stmtAluno->execute([':aluno_id' => $alunoId]);
            $aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);
            
            if (!$aluno) {
                throw new Exception("Aluno não encontrado");
            }
            
            // Processa as disciplinas do aluno
            $aluno['disciplinas'] = $disciplinasAluno;
            
            // Para cada aula, usa a disciplina atual do aluno se não tiver uma específica
            foreach ($aulas as &$aula) {
                if (empty($aula['disciplina_nome'])) {
                    // Se o aluno tem apenas uma disciplina, usa ela
                    if (count($disciplinasAluno) === 1) {
                        $aula['disciplina_nome'] = $disciplinasAluno[0]['nome'];
                    } else if (count($disciplinasAluno) > 1) {
                        // Se tem mais de uma, concatena todas
                        $aula['disciplina_nome'] = implode(' e ', array_map(function($d) {
                            return $d['nome'];
                        }, $disciplinasAluno));
                    }
                }
            }
            
            return [
                'success' => true,
                'data' => [
                    'aluno' => $aluno,
                    'aulas' => $aulas
                ]
            ];
            
        } catch (Exception $e) {
            error_log("Erro ao buscar aulas: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function buscarAluno($alunoId) {
        try {
            $this->requireMethod('GET');

            $stmt = $this->conn->prepare("
                SELECT 
                    a.*,
                    e.nome as escola_nome,
                    e.logo as escola_logo
                FROM alunos a
                LEFT JOIN escolas e ON a.escola_id = e.id
                WHERE a.id = ?
            ");
            
            $stmt->execute([$alunoId]);
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Dados do aluno e escola: " . print_r($aluno, true));
            
            if (!$aluno) {
                ApiResponse::notFound('Aluno não encontrado');
                return;
            }

            // Processa as disciplinas
            $disciplinaIds = $aluno['disciplina_ids'] ? explode(',', $aluno['disciplina_ids']) : [];
            $disciplinaNomes = $aluno['disciplinas'] ? explode(',', $aluno['disciplinas']) : [];
            $aluno['disciplinas'] = [];
            
            for ($i = 0; $i < count($disciplinaIds); $i++) {
                if (isset($disciplinaIds[$i]) && isset($disciplinaNomes[$i])) {
                    $aluno['disciplinas'][] = [
                        'id' => (int)$disciplinaIds[$i],
                        'nome' => $disciplinaNomes[$i]
                    ];
                }
            }

            // Remove campos temporários
            unset($aluno['disciplina_ids']);

            // Garante que os campos da escola estejam presentes
            if (!isset($aluno['escola_id'])) {
                $aluno['escola_id'] = null;
            }
            if (!isset($aluno['escola_nome'])) {
                $aluno['escola_nome'] = null;
            }

            error_log('Dados do aluno retornados: ' . json_encode($aluno));

            ApiResponse::success(['aluno' => $aluno], 'Aluno encontrado com sucesso');
        } catch (Exception $e) {
            error_log('Erro ao buscar aluno: ' . $e->getMessage());
            ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function getDadosAlunoPDF($alunoId) {
        try {
            // Primeiro, busca as disciplinas atuais do aluno
            $stmtDisciplinas = $this->conn->prepare("
                SELECT DISTINCT d.id, d.nome
                FROM aluno_disciplina ad
                JOIN disciplinas d ON ad.disciplina_id = d.id
                WHERE ad.aluno_id = :aluno_id
                ORDER BY d.nome
            ");
            $stmtDisciplinas->execute([':aluno_id' => $alunoId]);
            $disciplinasAluno = $stmtDisciplinas->fetchAll(PDO::FETCH_ASSOC);
            
            // Remove disciplinas duplicadas
            $disciplinasAluno = $this->limparDisciplinasDuplicadas($disciplinasAluno);
            
            error_log("Disciplinas atuais do aluno (após limpeza): " . print_r($disciplinasAluno, true));

            // Busca dados do aluno incluindo escola
            $stmt = $this->conn->prepare("
                SELECT 
                    a.*,
                    e.nome as escola_nome,
                    e.logo as escola_logo,
                    e.id as escola_id
                FROM alunos a
                LEFT JOIN escolas e ON a.escola_id = e.id
                WHERE a.id = ?
            ");
            
            error_log("Executando query para buscar dados do aluno ID: " . $alunoId);
            $stmt->execute([$alunoId]);
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Query SQL executada: " . $stmt->queryString);
            error_log("Parâmetros da query: " . json_encode([$alunoId]));
            error_log("Resultado completo da query: " . print_r($aluno, true));

            if (!$aluno) {
                error_log("Aluno não encontrado com ID: " . $alunoId);
                throw new Exception("Aluno não encontrado");
            }

            // Busca as aulas do aluno
            $stmt = $this->conn->prepare("
                SELECT 
                    ag.id,
                    ag.data_aula,
                    ag.horario,
                    ag.status,
                    ag.disciplina_id,
                    d.nome as disciplina_nome
                FROM agendamentos ag
                LEFT JOIN disciplinas d ON ag.disciplina_id = d.id
                WHERE ag.aluno_id = ?
                ORDER BY ag.data_aula ASC, ag.horario ASC
            ");
            
            $stmt->execute([$alunoId]);
            $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Para cada aula, usa a disciplina atual do aluno se não tiver uma específica
            foreach ($aulas as &$aula) {
                if (empty($aula['disciplina_nome'])) {
                    // Se o aluno tem apenas uma disciplina, usa ela
                    if (count($disciplinasAluno) === 1) {
                        $aula['disciplina_nome'] = $disciplinasAluno[0]['nome'];
                    } else if (count($disciplinasAluno) > 1) {
                        // Se tem mais de uma, concatena todas
                        $aula['disciplina_nome'] = implode(' e ', array_map(function($d) {
                            return $d['nome'];
                        }, $disciplinasAluno));
                    }
                }
            }

            return [
                'success' => true,
                'data' => [
                    'aluno' => $aluno,
                    'aulas' => $aulas
                ]
            ];

        } catch (Exception $e) {
            error_log('Erro ao buscar aluno: ' . $e->getMessage());
            ApiResponse::error($e->getMessage(), 500);
        }
    }
} 