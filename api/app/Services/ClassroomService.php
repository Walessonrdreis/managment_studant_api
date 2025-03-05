<?php

namespace App\Services;

use App\Models\Classroom;

class ClassroomService {
    /**
     * Serviço para gerenciar salas de aula.
     * Este serviço contém métodos para criar, ler, atualizar e excluir salas de aula.
     */

    public function createClassroom(array $data)
    {
        return Classroom::create($data);
    }

    public function getAllClassrooms()
    {
        return Classroom::all();
    }

    public function getClassroomById($id)
    {
        return Classroom::find($id);
    }

    public function updateClassroom($id, array $data)
    {
        $classroom = Classroom::find($id);
        if ($classroom) {
            $classroom->update($data);
            return $classroom;
        }
        return null;
    }

    public function deleteClassroom($id)
    {
        $classroom = Classroom::find($id);
        if ($classroom) {
            $classroom->delete();
            return true;
        }
        return false;
    }
}
