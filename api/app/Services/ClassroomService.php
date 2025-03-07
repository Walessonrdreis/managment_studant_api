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

    public function getClassroomWithSubjectById($id)
    {
        return Classroom::with('subject')->find($id);
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

    // Método para obter a disciplina associada a uma sala de aula
    public function getSubjectName($id)
    {
        $classroom = $this->getClassroomById($id);
        return $classroom ? ($classroom->subject ? $classroom->subject->name : 'Nenhuma disciplina associada') : null;
    }

    // Método para obter o professor associado a uma sala de aula
    public function getTeacherName($id)
    {
        $classroom = $this->getClassroomById($id);
        return $classroom ? ($classroom->teacher ? $classroom->teacher->name : 'Nenhum professor associado') : null;
    }

    // Método para obter todos os alunos associados a uma sala de aula
    public function getAllStudents($id)
    {
        $classroom = $this->getClassroomById($id);
        return $classroom ? $classroom->students : null;
    }

    // Método para verificar se a sala de aula tem alunos
    public function hasStudents($id)
    {
        $classroom = $this->getClassroomById($id);
        return $classroom ? $classroom->students()->count() > 0 : false;
    }
}
