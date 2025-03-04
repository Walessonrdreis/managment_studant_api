<?php
namespace App\Controllers;

use App\Models\Aluno;

class AlunoController
{
    private $alunoModel;
    
    public function __construct(Aluno $alunoModel)
    {
        $this->alunoModel = $alunoModel;
    }
    
    public function listarAlunos(): array
    {
        return $this->alunoModel->listarTodos();
    }
    
    public function buscarAluno(int $id): ?array
    {
        return $this->alunoModel->buscarPorId($id);
    }
    
    public function cadastrarAluno(array $dados): bool
    {
        return $this->alunoModel->cadastrar($dados);
    }
    
    public function atualizarAluno(int $id, array $dados): bool
    {
        return $this->alunoModel->atualizar($id, $dados);
    }
    
    public function excluirAluno(int $id): bool
    {
        return $this->alunoModel->excluir($id);
    }
} 