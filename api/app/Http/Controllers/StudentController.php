<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\StudentService;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Controlador para gerenciar estudantes.
     * Este controlador será responsável por criar, ler, atualizar e excluir estudantes.
     */

    // Listar todos os estudantes
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    // Criar um novo estudante
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'date_of_birth' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $student = Student::create($request->only('name', 'email', 'date_of_birth', 'user_id'));

        return response()->json(['success' => true, 'message' => 'Estudante criado com sucesso', 'data' => $student], 201);
    }

    // Obter detalhes de um estudante específico
    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }
        return response()->json($student);
    }

    // Atualizar informações de um estudante
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:students,email,' . $id,
        ]);

        $student->update($request->only('name', 'email'));

        return response()->json(['success' => true, 'message' => 'Estudante atualizado com sucesso', 'data' => $student]);
    }

    // Excluir um estudante
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }

        $student->delete();
        return response()->json(['message' => 'Estudante excluído com sucesso']);
    }
}
