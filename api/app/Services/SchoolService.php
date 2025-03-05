<?php

namespace App\Services;

use App\Models\School;

class SchoolService {
    /**
     * Serviço para gerenciar escolas.
     * Este serviço contém métodos para criar, ler, atualizar e excluir escolas.
     */

    public function createSchool(array $data)
    {
        return School::create($data);
    }

    public function getAllSchools()
    {
        return School::all();
    }

    public function getSchoolById($id)
    {
        return School::find($id);
    }

    public function updateSchool($id, array $data)
    {
        $school = School::find($id);
        if ($school) {
            $school->update($data);
            return $school;
        }
        return null;
    }

    public function deleteSchool($id)
    {
        $school = School::find($id);
        if ($school) {
            $school->delete();
            return true;
        }
        return false;
    }
}
