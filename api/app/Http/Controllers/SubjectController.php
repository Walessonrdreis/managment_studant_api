<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\SubjectService;
use App\Http\Controllers\Controller; // Adicione esta linha

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Controlador para gerenciar disciplinas.
     * Este controlador será responsável por criar, ler, atualizar e excluir disciplinas.
     */

    // Listar todas as disciplinas
    public function index()
    {
        $subjects = $this->subjectService->getAllSubjects();
        return response()->json($subjects);
    }

    // Criar uma nova disciplina
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subject = $this->subjectService->createSubject($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Disciplina criada com sucesso', 'data' => $subject], 201);
    }

    // Obter detalhes de uma disciplina específica
    public function show($id)
    {
        $subject = $this->subjectService->getSubjectById($id);
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], 404);
        }
        return response()->json($subject);
    }

    // Atualizar informações de uma disciplina
    public function update(Request $request, $id)
    {
        $subject = $this->subjectService->updateSubject($id, $request->only('name'));
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Disciplina atualizada com sucesso', 'data' => $subject]);
    }

    // Excluir uma disciplina
    public function destroy($id)
    {
        $this->subjectService->deleteSubject($id);
        return response()->json(['message' => 'Disciplina excluída com sucesso']);
    }
}
