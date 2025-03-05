<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    /**
     * Controlador para gerenciar professores.
     * Este controlador será responsável por criar, ler, atualizar e excluir professores.
     */

    // Listar todos os professores
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }

    // Criar um novo professor
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
        ]);

        $teacher = Teacher::create($request->only('name', 'email'));

        return response()->json(['success' => true, 'message' => 'Professor criado com sucesso', 'data' => $teacher], 201);
    }

    // Obter detalhes de um professor específico
    public function show($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }
        return response()->json($teacher);
    }

    // Atualizar informações de um professor
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:teachers,email,' . $id,
        ]);

        $teacher->update($request->only('name', 'email'));

        return response()->json(['success' => true, 'message' => 'Professor atualizado com sucesso', 'data' => $teacher]);
    }

    // Excluir um professor
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }

        $teacher->delete();
        return response()->json(['message' => 'Professor excluído com sucesso']);
    }
}
