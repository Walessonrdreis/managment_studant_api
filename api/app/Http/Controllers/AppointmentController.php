<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Controlador para gerenciar agendamentos.
     * Este controlador será responsável por criar, ler, atualizar e excluir agendamentos.
     */

    // Listar todos os agendamentos
    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments);
    }

    // Criar um novo agendamento
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $appointment = Appointment::create($request->only('date', 'time', 'user_id'));

        return response()->json(['success' => true, 'message' => 'Agendamento criado com sucesso', 'data' => $appointment], 201);
    }

    // Obter detalhes de um agendamento específico
    public function show($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }
        return response()->json($appointment);
    }

    // Atualizar informações de um agendamento
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }

        $request->validate([
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $appointment->update($request->only('date', 'time', 'user_id'));

        return response()->json(['success' => true, 'message' => 'Agendamento atualizado com sucesso', 'data' => $appointment]);
    }

    // Excluir um agendamento
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }

        $appointment->delete();
        return response()->json(['message' => 'Agendamento excluído com sucesso']);
    }
}
