<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    /**
     * Controlador para gerenciar disciplinas.
     * Este controlador será responsável por criar, ler, atualizar e excluir disciplinas.
     */

    // Listar todas as disciplinas
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    // Criar uma nova disciplina
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subject = Subject::create($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Disciplina criada com sucesso', 'data' => $subject], 201);
    }

    // Obter detalhes de uma disciplina específica
    public function show($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], 404);
        }
        return response()->json($subject);
    }

    // Atualizar informações de uma disciplina
    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $subject->update($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Disciplina atualizada com sucesso', 'data' => $subject]);
    }

    // Excluir uma disciplina
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], 404);
        }

        $subject->delete();
        return response()->json(['message' => 'Disciplina excluída com sucesso']);
    }
}
