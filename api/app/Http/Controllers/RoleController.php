<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Adicione esta linha
class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = $this->roleService->createRole($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role criada com sucesso',
            'data' => $role,
        ], 201);
    }

    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = $this->roleService->updateRole($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role atualizada com sucesso',
            'data' => $role,
        ]);
    }

    public function destroy($id)
    {
        $this->roleService->deleteRole($id);
        return response()->json(['message' => 'Role excluída com sucesso']);
    }
}
