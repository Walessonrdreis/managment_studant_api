<?php

namespace App\Services;

use App\Models\Grade;

class GradeService {
    /**
     * Serviço para gerenciar notas.
     * Este serviço contém métodos para atribuir e consultar notas dos estudantes.
     */

    public function assignGrade(array $data)
    {
        return Grade::create($data);
    }

    public function getAllGrades()
    {
        return Grade::all();
    }

    public function getGradeById($id)
    {
        return Grade::find($id);
    }

    public function updateGrade($id, array $data)
    {
        $grade = Grade::find($id);
        if ($grade) {
            $grade->update($data);
            return $grade;
        }
        return null;
    }

    public function deleteGrade($id)
    {
        $grade = Grade::find($id);
        if ($grade) {
            $grade->delete();
            return true;
        }
        return false;
    }
}
