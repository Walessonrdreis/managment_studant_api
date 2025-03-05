<?php

namespace App\Services;

use App\Models\StudentSubject;

class Student_SubjectService {
    /**
     * Serviço para gerenciar disciplinas dos estudantes.
     * Este serviço contém métodos para matricular estudantes em disciplinas.
     */

    public function enrollStudentInSubject(array $data)
    {
        return StudentSubject::create($data);
    }

    public function getAllStudentSubjects()
    {
        return StudentSubject::all();
    }

    public function getStudentSubjectById($id)
    {
        return StudentSubject::find($id);
    }

    public function updateStudentSubject($id, array $data)
    {
        $studentSubject = StudentSubject::find($id);
        if ($studentSubject) {
            $studentSubject->update($data);
            return $studentSubject;
        }
        return null;
    }

    public function deleteStudentSubject($id)
    {
        $studentSubject = StudentSubject::find($id);
        if ($studentSubject) {
            $studentSubject->delete();
            return true;
        }
        return false;
    }
}
