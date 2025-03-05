<?php

namespace App\Services;

use App\Models\Enrollment;

class EnrollmentService {
    /**
     * Serviço para gerenciar matrículas.
     * Este serviço contém métodos para matricular estudantes em disciplinas.
     */

    public function enrollStudent(array $data)
    {
        return Enrollment::create($data);
    }

    public function getAllEnrollments()
    {
        return Enrollment::all();
    }

    public function getEnrollmentById($id)
    {
        return Enrollment::find($id);
    }

    public function updateEnrollment($id, array $data)
    {
        $enrollment = Enrollment::find($id);
        if ($enrollment) {
            $enrollment->update($data);
            return $enrollment;
        }
        return null;
    }

    public function deleteEnrollment($id)
    {
        $enrollment = Enrollment::find($id);
        if ($enrollment) {
            $enrollment->delete();
            return true;
        }
        return false;
    }
}
