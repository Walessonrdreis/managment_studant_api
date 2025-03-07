<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classroom extends Model
{
    use HasFactory;
    /**
     * Modelo para gerenciar salas de aula.
     * Este modelo representa a tabela de salas de aula no banco de dados.
     */

    protected $fillable = ['name', 'capacity', 'school_id', 'subject_id', 'student_id', 'teacher_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
