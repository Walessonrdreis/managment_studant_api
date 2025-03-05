<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    /**
     * Controlador para gerenciar escolas.
     * Este controlador será responsável por criar, ler, atualizar e excluir escolas.
     */

    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        // Criar uma nova instância do modelo School
        $school = new School();
        $school->nome = $request->input('nome');

        // Salvar a escola no banco de dados
        $school->save();

        // Retornar resposta
        return response()->json([
            'success' => true,
            'message' => 'Escola cadastrada com sucesso',
            'data' => [
                'id' => $school->id,
                'nome' => $school->nome,
            ]
        ], 201);
    }
}
