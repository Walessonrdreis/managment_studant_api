<?php

namespace App\Services;

use App\Models\Subject;

class SubjectService {
    /**
     * Serviço para gerenciar disciplinas.
     * Este serviço contém métodos para criar, ler, atualizar e excluir disciplinas.
     */

    public function createSubject(array $data)
    {
        return Subject::create($data);
    }

    public function getAllSubjects()
    {
        return Subject::all();
    }

    public function getSubjectById($id)
    {
        return Subject::find($id);
    }

    public function updateSubject($id, array $data)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->update($data);
            return $subject;
        }
        return null;
    }

    public function deleteSubject($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->delete();
            return true;
        }
        return false;
    }
}
