<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Services\ClassroomService;

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
        $classrooms = Classroom::all();
        return response()->json($classrooms);
    }

    // Criar uma nova sala de aula
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $classroom = Classroom::create($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Sala de aula criada com sucesso', 'data' => $classroom], 201);
    }

    // Obter detalhes de uma sala de aula específica
    public function show($id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return response()->json(['message' => 'Sala de aula não encontrada'], 404);
        }
        return response()->json($classroom);
    }

    // Atualizar informações de uma sala de aula
    public function update(Request $request, $id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return response()->json(['message' => 'Sala de aula não encontrada'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $classroom->update($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Sala de aula atualizada com sucesso', 'data' => $classroom]);
    }

    // Excluir uma sala de aula
    public function destroy($id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return response()->json(['message' => 'Sala de aula não encontrada'], 404);
        }

        $classroom->delete();
        return response()->json(['message' => 'Sala de aula excluída com sucesso']);
    }
}
