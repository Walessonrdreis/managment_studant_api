<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;
    /**
     * Modelo para gerenciar notas.
     * Este modelo representa a tabela de notas no banco de dados.
     */

    protected $fillable = ['student_id', 'subject_id', 'grade', 'school_id'];

    /**
     * Define a relação com o modelo Student.
     * Uma nota pertence a um estudante.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Define a relação com o modelo Subject.
     * Uma nota pertence a uma disciplina.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Define a relação com o modelo School.
     * Uma nota pertence a uma escola.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
