<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * Modelo para gerenciar presenças.
     * Este modelo representa a tabela de presenças no banco de dados.
     */

    protected $fillable = ['student_id', 'date', 'status', 'school_id'];

    /**
     * Define a relação com o modelo Student.
     * Uma presença pertence a um estudante.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Define a relação com o modelo School.
     * Uma presença pertence a uma escola.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
