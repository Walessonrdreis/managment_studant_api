<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\StudentService;
use App\Http\Controllers\Controller;
use App\Models\User;

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

        $user = User::find($request->user_id);

        // Verifique se o usuário tem o papel apropriado
        if (!$user || !$user->role || strtolower($user->role->name) !== 'student') {
            return response()->json(['message' => 'O usuário deve ter o papel de estudante para se cadastrar.'], 403);
        }

        try {
            $student = $this->studentService->createStudent($request->only('name', 'email', 'date_of_birth', 'user_id'));
            return response()->json(['success' => true, 'message' => 'Estudante criado com sucesso', 'data' => $student], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500); // Retorna 500 para erros inesperados
        }
    }

    // Obter detalhes de um estudante específico
    public function show($id)
    {
        $student = Student::findOrFail($id); // Isso lançará uma exceção se o aluno não for encontrado
        return view('student', compact('student')); // Certifique-se de que o nome da view está correto
    }

    // Atualizar informações de um estudante
    public function update(Request $request, $id)
    {
        $this->validateStudent($request);
        $student = $this->studentService->updateStudent($id, $request->only('name', 'email', 'date_of_birth', 'user_id'));
        return $student ? response()->json(['success' => true, 'message' => 'Estudante atualizado com sucesso', 'data' => $student]) : response()->json(['message' => 'Estudante não encontrado'], 404);
    }

    // Excluir um estudante
    public function destroy($id)
    {
        $this->studentService->deleteStudent($id);
        return response()->json(['message' => 'Estudante excluído com sucesso']);
    }

    protected function validateStudent(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $request->id,
            'date_of_birth' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);
    }
}
