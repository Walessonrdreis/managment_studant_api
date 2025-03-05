<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Controlador para gerenciar presenças.
     * Este controlador será responsável por registrar e consultar a presença dos estudantes.
     */

    // Listar todas as presenças
    public function index()
    {
        $attendances = Attendance::all();
        return response()->json($attendances);
    }

    // Registrar uma nova presença
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|string',
        ]);

        $attendance = Attendance::create($request->only('student_id', 'date', 'status'));

        return response()->json(['success' => true, 'message' => 'Presença registrada com sucesso', 'data' => $attendance], 201);
    }

    // Obter detalhes de uma presença específica
    public function show($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json(['message' => 'Presença não encontrada'], 404);
        }
        return response()->json($attendance);
    }

    // Atualizar informações de uma presença
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json(['message' => 'Presença não encontrada'], 404);
        }

        $request->validate([
            'student_id' => 'sometimes|required|exists:students,id',
            'date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string',
        ]);

        $attendance->update($request->only('student_id', 'date', 'status'));

        return response()->json(['success' => true, 'message' => 'Presença atualizada com sucesso', 'data' => $attendance]);
    }

    // Excluir uma presença
    public function destroy($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json(['message' => 'Presença não encontrada'], 404);
        }

        $attendance->delete();
        return response()->json(['message' => 'Presença excluída com sucesso']);
    }
}
