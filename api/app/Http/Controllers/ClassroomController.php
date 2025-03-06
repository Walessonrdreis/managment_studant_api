<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Services\ClassroomService;
use App\Http\Controllers\Controller; // Adicione esta linha
class ClassroomController extends Controller
{
    protected $classroomService;

    public function __construct(ClassroomService $classroomService)
    {
        $this->classroomService = $classroomService;
    }

    /**
     * Controlador para gerenciar salas de aula.
     * Este controlador será responsável por criar, ler, atualizar e excluir salas de aula.
     */

    // Listar todas as salas de aula
    public function index()
    {
        $classrooms = $this->classroomService->getAllClassrooms();
        return response()->json($classrooms);
    }

    // Criar uma nova sala de aula
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $classroom = $this->classroomService->createClassroom($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Sala de aula criada com sucesso', 'data' => $classroom], 201);
    }

    // Obter detalhes de uma sala de aula específica
    public function show($id)
    {
        $classroom = $this->classroomService->getClassroomById($id);
        if (!$classroom) {
            return response()->json(['message' => 'Sala de aula não encontrada'], 404);
        }
        return response()->json($classroom);
    }

    // Atualizar informações de uma sala de aula
    public function update(Request $request, $id)
    {
        $classroom = $this->classroomService->updateClassroom($id, $request->only('name'));
        if (!$classroom) {
            return response()->json(['message' => 'Sala de aula não encontrada'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Sala de aula atualizada com sucesso', 'data' => $classroom]);
    }

    // Excluir uma sala de aula
    public function destroy($id)
    {
        $this->classroomService->deleteClassroom($id);
        return response()->json(['message' => 'Sala de aula excluída com sucesso']);
    }
}
