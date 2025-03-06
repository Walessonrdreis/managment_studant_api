<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\StudentService;
use App\Http\Controllers\Controller; // Adicione esta linha
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
        $students = $this->studentService->getAllStudents();
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

        $student = $this->studentService->createStudent($request->only('name', 'email', 'date_of_birth', 'user_id'));

        return response()->json(['success' => true, 'message' => 'Estudante criado com sucesso', 'data' => $student], 201);
    }

    // Obter detalhes de um estudante específico
    public function show($id)
    {
        $student = $this->studentService->getStudentById($id);
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }
        return response()->json($student);
    }

    // Atualizar informações de um estudante
    public function update(Request $request, $id)
    {
        $student = $this->studentService->getStudentById($id);
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:students,email,' . $id,
        ]);

        $student = $this->studentService->updateStudent($id, $request->only('name', 'email'));

        return response()->json(['success' => true, 'message' => 'Estudante atualizado com sucesso', 'data' => $student]);
    }

    // Excluir um estudante
    public function destroy($id)
    {
        $student = $this->studentService->getStudentById($id);
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }

        $this->studentService->deleteStudent($id);
        return response()->json(['message' => 'Estudante excluído com sucesso']);
    }
}
