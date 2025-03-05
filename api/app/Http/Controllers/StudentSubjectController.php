<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSubject;

class StudentSubjectController extends Controller
{
    /**
     * Controlador para gerenciar disciplinas dos estudantes.
     * Este controlador será responsável por matricular estudantes em disciplinas.
     */

    // Listar todas as matrículas de estudantes em disciplinas
    public function index()
    {
        $studentSubjects = StudentSubject::all();
        return response()->json($studentSubjects);
    }

    // Matricular um estudante em uma disciplina
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $studentSubject = StudentSubject::create($request->only('student_id', 'subject_id'));

        return response()->json(['success' => true, 'message' => 'Estudante matriculado na disciplina com sucesso', 'data' => $studentSubject], 201);
    }

    // Obter detalhes de uma matrícula específica
    public function show($id)
    {
        $studentSubject = StudentSubject::find($id);
        if (!$studentSubject) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }
        return response()->json($studentSubject);
    }

    // Atualizar informações de uma matrícula
    public function update(Request $request, $id)
    {
        $studentSubject = StudentSubject::find($id);
        if (!$studentSubject) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }

        $request->validate([
            'student_id' => 'sometimes|required|exists:students,id',
            'subject_id' => 'sometimes|required|exists:subjects,id',
        ]);

        $studentSubject->update($request->only('student_id', 'subject_id'));

        return response()->json(['success' => true, 'message' => 'Matrícula atualizada com sucesso', 'data' => $studentSubject]);
    }

    // Excluir uma matrícula
    public function destroy($id)
    {
        $studentSubject = StudentSubject::find($id);
        if (!$studentSubject) {
            return response()->json(['message' => 'Matrícula não encontrada'], 404);
        }

        $studentSubject->delete();
        return response()->json(['message' => 'Matrícula excluída com sucesso']);
    }
}
