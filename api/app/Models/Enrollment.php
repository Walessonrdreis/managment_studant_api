<?php

// managment_studant_api/api/app/Models/Enrollment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'subject_id', 'school_id']; // Adicione school_id

    /**
     * Define a relação com o modelo Student.
     * Uma matrícula pertence a um estudante.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Define a relação com o modelo Subject.
     * Uma matrícula pertence a uma disciplina.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Define a relação com o modelo School.
     * Uma matrícula pertence a uma escola.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}