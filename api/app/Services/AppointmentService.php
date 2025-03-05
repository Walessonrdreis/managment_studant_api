<?php

namespace App\Services;

use App\Models\Appointment;

class AppointmentService {
    /**
     * Serviço para gerenciar agendamentos.
     * Este serviço contém métodos para criar, ler, atualizar e excluir agendamentos.
     */

    public function createAppointment(array $data)
    {
        return Appointment::create($data);
    }

    public function getAllAppointments()
    {
        return Appointment::all();
    }

    public function getAppointmentById($id)
    {
        return Appointment::find($id);
    }

    public function updateAppointment($id, array $data)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->update($data);
            return $appointment;
        }
        return null;
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->delete();
            return true;
        }
        return false;
    }
}
