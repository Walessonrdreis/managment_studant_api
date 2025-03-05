<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Controlador para gerenciar usuários.
     * Este controlador será responsável por criar, ler, atualizar e excluir usuários.
     */

    // Listar todos os usuários
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Criar um novo usuário usando o serviço
        $user = $this->userService->createUser($request->only('name', 'email', 'password', 'role_id'));

        return response()->json([
            'success' => true,
            'message' => 'Usuário criado com sucesso',
            'data' => $user,
        ], 201);
    }

    // Obter detalhes de um usuário específico
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user);
    }

    // Atualizar informações de um usuário
    public function update(Request $request, $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Validação dos dados recebidos
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        // Atualizar informações do usuário
        $user = $this->userService->updateUser($id, $request->only('name', 'email', 'password', 'role_id'));

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso',
            'data' => $user,
        ]);
    }

    // Excluir um usuário
    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $this->userService->deleteUser($id);
        return response()->json(['message' => 'Usuário excluído com sucesso']);
    }
}
