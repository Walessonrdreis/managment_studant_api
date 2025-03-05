<?php

namespace App\Services;

use App\Models\Student;

class StudentService {
    /**
     * Serviço para gerenciar estudantes.
     * Este serviço contém métodos para criar, ler, atualizar e excluir estudantes.
     */

    public function createStudent(array $data)
    {
        return Student::create($data);
    }

    public function getAllStudents()
    {
        return Student::all();
    }

    public function getStudentById($id)
    {
        return Student::find($id);
    }

    public function updateStudent($id, array $data)
    {
        $student = Student::find($id);
        if ($student) {
            $student->update($data);
            return $student;
        }
        return null;
    }

    public function deleteStudent($id)
    {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return true;
        }
        return false;
    }
}
