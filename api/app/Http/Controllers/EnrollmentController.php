<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use App\Http\Controllers\Controller; // Adicione esta linha
class EnrollmentController extends Controller
{
    /**
     * Controlador para gerenciar matrículas.
     * Este controlador será responsável por matricular estudantes em disciplinas.
     */

    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    // Listar todas as matrículas
    public function index()
    {
        $enrollments = $this->enrollmentService->getAllEnrollments();
        return response()->json($enrollments);
    }

    // Criar uma nova matrícula
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $enrollment = $this->enrollmentService->enrollStudent($request->only('student_id', 'subject_id'));

        return response()->json(['success' => true, 'message' => 'Matrícula criada com sucesso', 'data' => $enrollment], 201);
    }

    // Obter detalhes de uma matrícula específica
    public function show($id)
    {
        $enrollment = $this->enrollmentService->getEnrollmentById($id);
        if (!$enrollment) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }
        return response()->json($enrollment);
    }

    // Atualizar informações de uma matrícula
    public function update(Request $request, $id)
    {
        $enrollment = $this->enrollmentService->getEnrollmentById($id);
        if (!$enrollment) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }

        $request->validate([
            'student_id' => 'sometimes|required|exists:students,id',
            'subject_id' => 'sometimes|required|exists:subjects,id',
        ]);

        $enrollment = $this->enrollmentService->updateEnrollment($id, $request->only('student_id', 'subject_id'));

        return response()->json(['success' => true, 'message' => 'Matrícula atualizada com sucesso', 'data' => $enrollment]);
    }

    // Excluir uma matrícula
    public function destroy($id)
    {
        $enrollment = $this->enrollmentService->getEnrollmentById($id);
        if (!$enrollment) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }

        $this->enrollmentService->deleteEnrollment($id);
        return response()->json(['message' => 'Matrícula excluída com sucesso']);
    }
}
