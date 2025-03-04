<?php

class Aluno {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $matricula;
    private $disciplinas = [];
    private $aulas = [];
    private $created_at;
    private $updated_at;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->nome = $data['nome'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->telefone = $data['telefone'] ?? null;
        $this->matricula = $data['matricula'] ?? null;
        $this->disciplinas = $data['disciplinas'] ?? [];
        $this->aulas = $data['aulas'] ?? [];
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNome(): ?string {
        return $this->nome;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getTelefone(): ?string {
        return $this->telefone;
    }

    public function getMatricula(): ?string {
        return $this->matricula;
    }

    public function getDisciplinas(): array {
        return $this->disciplinas;
    }

    public function getAulas(): array {
        return $this->aulas;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTelefone(?string $telefone): void {
        $this->telefone = $telefone;
    }

    public function setMatricula(string $matricula): void {
        $this->matricula = $matricula;
    }

    public function setDisciplinas(array $disciplinas): void {
        $this->disciplinas = $disciplinas;
    }

    public function setAulas(array $aulas): void {
        $this->aulas = $aulas;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'matricula' => $this->matricula,
            'disciplinas' => $this->disciplinas,
            'aulas' => $this->aulas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public function validate(): array {
        $errors = [];

        if (empty($this->nome)) {
            $errors['nome'] = 'Nome é obrigatório';
        } elseif (strlen($this->nome) < 3) {
            $errors['nome'] = 'Nome deve ter no mínimo 3 caracteres';
        } elseif (strlen($this->nome) > 255) {
            $errors['nome'] = 'Nome deve ter no máximo 255 caracteres';
        }

        if (empty($this->email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        } elseif (strlen($this->email) > 255) {
            $errors['email'] = 'Email deve ter no máximo 255 caracteres';
        }

        if (!empty($this->telefone)) {
            if (!preg_match('/^\(\d{2}\) \d{4,5}-\d{4}$/', $this->telefone)) {
                $errors['telefone'] = 'Telefone deve estar no formato (XX) XXXXX-XXXX';
            }
        }

        foreach ($this->aulas as $aula) {
            if (!empty($aula['data'])) {
                $data = DateTime::createFromFormat('Y-m-d', $aula['data']);
                if (!$data || $data->format('Y-m-d') !== $aula['data']) {
                    $errors['aulas'][] = 'Data inválida: ' . $aula['data'];
                }
            }

            if (!empty($aula['horario'])) {
                if (!preg_match('/^\d{2}:\d{2}$/', $aula['horario'])) {
                    $errors['aulas'][] = 'Horário inválido: ' . $aula['horario'];
                }
            }
        }

        return $errors;
    }
} 