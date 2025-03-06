<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentSubject extends Model
{
    use HasFactory;
    /**
     * Modelo para gerenciar disciplinas dos estudantes.
     * Este modelo representa a tabela de disciplinas dos estudantes no banco de dados.
     */

    protected $fillable = ['student_id', 'subject_id', 'school_id'];

    /**
     * Define a relação com o modelo Student.
     * Uma associação de disciplina pertence a um estudante.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Define a relação com o modelo Subject.
     * Uma associação de disciplina pertence a uma disciplina.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Define a relação com o modelo School.
     * Uma associação de disciplina pertence a uma escola.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
