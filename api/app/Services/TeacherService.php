<?php

namespace App\Services;

use App\Models\Teacher;

class TeacherService {
    /**
     * Serviço para gerenciar professores.
     * Este serviço contém métodos para criar, ler, atualizar e excluir professores.
     */

    public function createTeacher(array $data)
    {
        return Teacher::create($data);
    }

    public function getAllTeachers()
    {
        return Teacher::all();
    }

    public function getTeacherById($id)
    {
        return Teacher::find($id);
    }

    public function updateTeacher($id, array $data)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacher->update($data);
            return $teacher;
        }
        return null;
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacher->delete();
            return true;
        }
        return false;
    }
}
