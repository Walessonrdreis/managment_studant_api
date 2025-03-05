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
        // Certifique-se de que o role_id está incluído nos dados
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
}
