<?php

namespace App\Services;

use App\Models\User;

class UserService {
    /**
     * Serviço para gerenciar usuários.
     * Este serviço contém métodos para criar, ler, atualizar e excluir usuários.
     */

    public function createUser($data)
    {
        // Criptografar a senha antes de criar o usuário
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    public function updateUser($id, $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data); // Atualiza os dados do usuário, incluindo role_id
            return $user;
        }
        return null;
    }

    public function getAllUsers()
    {
        return User::all(); // Retorna todos os usuários
    }

    public function getUserById($id)
    {
        return User::find($id); // Retorna um usuário pelo ID
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }
}
