<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Controlador para gerenciar usuários.
     * Este controlador será responsável por criar, ler, atualizar e excluir usuários.
     */

    // Listar todos os usuários
    public function index()
    {
        $users = User::all();
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
        ]);

        // Criar um novo usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuário criado com sucesso',
            'data' => $user,
        ], 201);
    }

    // Obter detalhes de um usuário específico
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user);
    }

    // Atualizar informações de um usuário
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Validação dos dados recebidos
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        // Atualizar informações do usuário
        $user->update($request->only('name', 'email', 'password'));

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso',
            'data' => $user,
        ]);
    }

    // Excluir um usuário
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuário excluído com sucesso']);
    }
}
