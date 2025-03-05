<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Services\SchoolService;

class SchoolController extends Controller
{
    protected $schoolService;

    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    /**
     * Controlador para gerenciar escolas.
     * Este controlador será responsável por criar, ler, atualizar e excluir escolas.
     */

    // Listar todas as escolas
    public function index()
    {
        $schools = $this->schoolService->getAllSchools();
        return response()->json($schools);
    }

    // Criar uma nova escola
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Criar uma nova instância do modelo School
        $school = $this->schoolService->createSchool($request->only('name'));

        // Retornar resposta
        return response()->json([
            'success' => true,
            'message' => 'Escola cadastrada com sucesso',
            'data' => $school
        ], 201);
    }

    // Obter detalhes de uma escola específica
    public function show($id)
    {
        $school = $this->schoolService->getSchoolById($id);
        if (!$school) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }
        return response()->json($school);
    }

    // Atualizar informações de uma escola
    public function update(Request $request, $id)
    {
        $school = $this->schoolService->getSchoolById($id);
        if (!$school) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $school = $this->schoolService->updateSchool($id, $request->only('name'));

        return response()->json(['success' => true, 'message' => 'Escola atualizada com sucesso', 'data' => $school]);
    }

    // Excluir uma escola
    public function destroy($id)
    {
        $school = $this->schoolService->getSchoolById($id);
        if (!$school) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }

        $this->schoolService->deleteSchool($id);
        return response()->json(['message' => 'Escola excluída com sucesso']);
    }
}
