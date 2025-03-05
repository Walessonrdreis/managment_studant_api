<?php

namespace App\Services;

use App\Models\Attendance;

class AttendanceService {
    /**
     * Serviço para gerenciar presenças.
     * Este serviço contém métodos para registrar e consultar a presença dos estudantes.
     */

    public function recordAttendance(array $data)
    {
        return Attendance::create($data);
    }

    public function getAllAttendances()
    {
        return Attendance::all();
    }

    public function getAttendanceById($id)
    {
        return Attendance::find($id);
    }

    public function updateAttendance($id, array $data)
    {
        $attendance = Attendance::find($id);
        if ($attendance) {
            $attendance->update($data);
            return $attendance;
        }
        return null;
    }

    public function deleteAttendance($id)
    {
        $attendance = Attendance::find($id);
        if ($attendance) {
            $attendance->delete();
            return true;
        }
        return false;
    }
}
