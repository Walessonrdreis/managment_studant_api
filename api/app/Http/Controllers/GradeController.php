<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Services\GradeService;

class GradeController extends Controller
{
    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Controlador para gerenciar notas.
     * Este controlador será responsável por atribuir e consultar notas dos estudantes.
     */

    // Listar todas as notas
    public function index()
    {
        $grades = $this->gradeService->getAllGrades();
        return response()->json($grades);
    }

    // Criar uma nova nota
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric',
        ]);

        $grade = $this->gradeService->assignGrade($request->only('student_id', 'subject_id', 'grade'));

        return response()->json(['success' => true, 'message' => 'Nota criada com sucesso', 'data' => $grade], 201);
    }

    // Obter detalhes de uma nota específica
    public function show($id)
    {
        $grade = $this->gradeService->getGradeById($id);
        if (!$grade) {
            return response()->json(['message' => 'Nota não encontrada'], 404);
        }
        return response()->json($grade);
    }

    // Atualizar informações de uma nota
    public function update(Request $request, $id)
    {
        $grade = $this->gradeService->getGradeById($id);
        if (!$grade) {
            return response()->json(['message' => 'Nota não encontrada'], 404);
        }

        $request->validate([
            'student_id' => 'sometimes|required|exists:students,id',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'grade' => 'sometimes|required|numeric',
        ]);

        $grade = $this->gradeService->updateGrade($id, $request->only('student_id', 'subject_id', 'grade'));

        return response()->json(['success' => true, 'message' => 'Nota atualizada com sucesso', 'data' => $grade]);
    }

    // Excluir uma nota
    public function destroy($id)
    {
        $grade = $this->gradeService->getGradeById($id);
        if (!$grade) {
            return response()->json(['message' => 'Nota não encontrada'], 404);
        }

        $this->gradeService->deleteGrade($id);
        return response()->json(['message' => 'Nota excluída com sucesso']);
    }
}
