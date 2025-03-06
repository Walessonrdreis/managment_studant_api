<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleService {
    /**
     * Serviço para gerenciar papéis.
     * Este serviço contém métodos para criar, ler, atualizar e excluir papéis.
     */

    public function createRole(array $data)
    {
        return Role::create($data);
    }

    public function getAllRoles()
    {
        return Role::all();
    }

    public function getRoleById($id)
    {
        return Role::findOrFail($id);
    }

    public function updateRole($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $role;
    }
}
