<?php
namespace App\Models;

class Aluno
{
    public function listarTodos(): array
    {
        return [
            ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@exemplo.com'],
            ['id' => 2, 'nome' => 'Maria Santos', 'email' => 'maria@exemplo.com']
        ];
    }
    
    public function buscarPorId(int $id): ?array
    {
        if ($id === 1) {
            return [
                'id' => 1,
                'nome' => 'João Silva',
                'email' => 'joao@exemplo.com',
                'telefone' => '(11) 98765-4321',
                'data_nascimento' => '1995-05-15'
            ];
        }
        
        return null;
    }
    
    public function cadastrar(array $dados): bool
    {
        return true;
    }
    
    public function atualizar(int $id, array $dados): bool
    {
        return true;
    }
    
    public function excluir(int $id): bool
    {
        return true;
    }
} 
 