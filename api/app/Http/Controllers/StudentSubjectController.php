<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSubject;
use App\Services\Student_SubjectService;
use App\Http\Controllers\Controller; // Adicione esta linha
use App\Models\Subject;

class StudentSubjectController extends Controller
{
    /**
     * Controlador para gerenciar disciplinas dos estudantes.
     * Este controlador será responsável por matricular estudantes em disciplinas.
     */

    protected $studentSubjectService;

    public function __construct(Student_SubjectService $studentSubjectService)
    {
        $this->studentSubjectService = $studentSubjectService;
    }

    // Listar todas as matrículas de estudantes em disciplinas
    public function index()
    {
        $studentSubjects = $this->studentSubjectService->getAllStudentSubjects();
        return response()->json($studentSubjects);
    }

    // Matricular um estudante em uma disciplina
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required', // Remova a validação de existência aqui
        ]);

        // Verifica se a disciplina existe
        $subject = Subject::find($request->subject_id);
        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Não há disciplina para registrar. Aguarde a adição de uma nova disciplina.'
            ], 404); // Retorna um status 404 se a disciplina não existir
        }

        // Cria a matrícula se a disciplina existir
        $studentSubject = StudentSubject::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estudante matriculado na disciplina com sucesso',
            'data' => $studentSubject,
        ], 201);
    }

    // Obter detalhes de uma matrícula específica
    public function show($id)
    {
        $studentSubject = $this->studentSubjectService->getStudentSubjectById($id);
        if (!$studentSubject) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }
        return response()->json($studentSubject);
    }

    // Atualizar informações de uma matrícula
    public function update(Request $request, $id)
    {
        $studentSubject = $this->studentSubjectService->updateStudentSubject($id, $request->only('student_id', 'subject_id'));
        if (!$studentSubject) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Matrícula atualizada com sucesso', 'data' => $studentSubject]);
    }

    // Excluir uma matrícula
    public function destroy($id)
    {
        $this->studentSubjectService->deleteStudentSubject($id);
        return response()->json(['message' => 'Matrícula excluída com sucesso']);
    }
}
