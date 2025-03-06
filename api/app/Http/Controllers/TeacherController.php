<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Services\TeacherService;
use App\Http\Controllers\Controller; // Adicione esta linha

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index()
    {
        return response()->json($this->teacherService->getAllTeachers());
    }

    public function store(Request $request)
    {
        $this->validateTeacher($request);
        $user = auth()->user();
        if (!$user || !$user->role || strtolower($user->role->name) !== 'teacher') {
            return response()->json(['message' => 'O usuário deve ter o papel de professor para se cadastrar.'], 403);
        }
        $teacher = $this->teacherService->createTeacher($request->only('name', 'subject', 'user_id'));
        return response()->json(['success' => true, 'message' => 'Professor criado com sucesso', 'data' => $teacher], 201);
    }

    public function show($id)
    {
        $teacher = $this->teacherService->getTeacherById($id);
        return $teacher ? response()->json($teacher) : response()->json(['message' => 'Professor não encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $this->validateTeacher($request);
        $teacher = $this->teacherService->updateTeacher($id, $request->only('name', 'subject'));
        return $teacher ? response()->json(['success' => true, 'message' => 'Professor atualizado com sucesso', 'data' => $teacher]) : response()->json(['message' => 'Professor não encontrado'], 404);
    }

    public function destroy($id)
    {
        $this->teacherService->deleteTeacher($id);
        return response()->json(['message' => 'Professor excluído com sucesso']);
    }

    protected function validateTeacher(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);
    }
}
