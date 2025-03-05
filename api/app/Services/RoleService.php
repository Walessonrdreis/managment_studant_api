<?php

namespace App\Services;

use App\Models\Role;

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
        return Role::find($id);
    }

    public function updateRole($id, array $data)
    {
        $role = Role::find($id);
        if ($role) {
            $role->update($data);
            return $role;
        }
        return null;
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return true;
        }
        return false;
    }
}
