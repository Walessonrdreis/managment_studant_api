<?php

namespace App\Services;

use App\Models\Role;

class RoleService {
    /**
     * Serviço para gerenciar papéis.
     * Este serviço contém métodos para criar, ler, atualizar e excluir papéis.
     */

    public function createRole($data)
    {
        return Role::create($data);
    }

    public function updateRole($id, $data)
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

    public function getAllRoles()
    {
        return Role::all();
    }

    public function getRoleById($id)
    {
        return Role::find($id);
    }
}
